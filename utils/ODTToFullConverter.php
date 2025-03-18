<?php

//HTML Handlers
require_once "utils/handlers/html/DocumentStructureHTMLHandler.php";
require_once "utils/handlers/html/MetadataHTMLHandler.php";
require_once "utils/handlers/html/StyleHTMLHandler.php";
require_once "utils/handlers/html/HeadingHTMLHandler.php";
require_once "utils/handlers/html/ListHTMLHandler.php";
require_once "utils/handlers/html/ParagraphHTMLHandler.php";
require_once "utils/handlers/html/TableHTMLHandler.php";
require_once "utils/handlers/html/ImageHTMLHandler.php";
require_once "utils/handlers/html/TextStyleHTMLHandler.php";
require_once "utils/handlers/html/FrameHTMLHandler.php";
require_once "utils/handlers/html/LinkHTMLHandler.php";
require_once "utils/handlers/html/MathHTMLHandler.php";
require_once "utils/handlers/html/DrawingHTMLHandler.php";

//CSS Handlers
require_once "utils/handlers/css/FontCSSHandler.php";
require_once "utils/handlers/css/ParagraphCSSHandler.php";
require_once "utils/handlers/css/TableCSSHandler.php";
require_once "utils/handlers/css/HeadingStyleHandler.php";
require_once "utils/handlers/css/ListCSSHandler.php";
require_once "utils/handlers/css/ImageCSSHandler.php";
require_once "utils/handlers/css/FrameCSSHandler.php";
require_once "utils/handlers/css/LinkCSSHandler.php";
require_once "utils/handlers/css/PageCSSHandler.php";
require_once "utils/handlers/css/MathCSSHandler.php";
require_once "utils/handlers/css/DrawingCSSHandler.php";

class ODTToFullConverter
{
    private DocumentStructureHTMLHandler $htmlHandler;
    private CSSHandler $cssHandler;
    private array $conversionWarnings = [];
    private array $conversionErrors = [];
    private array $conversionStats = [];

    /**
     * Constructor - Sets up handler chains
     */
    public function __construct()
    {
        // Configuration of HTML handlers chain
        $this->htmlHandler = new DocumentStructureHTMLHandler();
        $this->htmlHandler
            ->setNext(new MetadataHTMLHandler())
            ->setNext(new StyleHTMLHandler())
            ->setNext(new HeadingHTMLHandler())
            ->setNext(new ListHTMLHandler())
            ->setNext(new ParagraphHTMLHandler())
            ->setNext(new TableHTMLHandler())
            ->setNext(new ImageHTMLHandler())
            ->setNext(new TextStyleHTMLHandler())
            ->setNext(new FrameHTMLHandler())
            ->setNext(new LinkHTMLHandler())
            ->setNext(new MathHTMLHandler())
            ->setNext(new DrawingHTMLHandler());

        // Configuration of CSS handlers chain
        $this->cssHandler = new FontCSSHandler();
        $this->cssHandler
            ->setNext(new ParagraphCSSHandler())
            ->setNext(new TableCSSHandler())
            ->setNext(new HeadingStyleHandler())
            ->setNext(new ListCSSHandler())
            ->setNext(new ImageCSSHandler())
            ->setNext(new FrameCSSHandler())
            ->setNext(new LinkCSSHandler())
            ->setNext(new PageCSSHandler())
            ->setNext(new MathCSSHandler())
            ->setNext(new DrawingCSSHandler());
    }

    /**
     * Converts an ODT file to HTML
     *
     * @param string $odtFilePath Path to the ODT file
     * @param array $options Optional conversion options
     * @return array Conversion result containing HTML, warnings, errors and stats
     * @throws RunTimeException|Exception
     */
    public function convert(string $odtFilePath, array $options = []): array
    {
        $this->resetConversionState();
        $this->conversionStats['start_time'] = microtime(true);

        // Set default options
        $defaultOptions = [
            'embedImages' => true,
            'extractMetadata' => true,
            'sanitizeHTML' => true,
            'outputFormat' => 'html', // can be 'html' or 'xhtml'
            'cssStrategy' => 'embed',  // can be 'embed' or 'external'
            'imagePath' => '',         // path for image extraction if not embedded
        ];
        $options = array_merge($defaultOptions, $options);

        // Open ODT file
        $zip = new ZipArchive();
        if($zip->open($odtFilePath) !== TRUE){
            $this->conversionErrors[] = "Could not open ODT file: $odtFilePath";
            $zip->close();
            throw new RuntimeException("Could not open ODT file.");
        }

        // Process content.xml
        $contentXML = $zip->getFromName("content.xml");
        if($contentXML === false){
            $this->conversionErrors[] = "Could not extract content.xml from ODT file";
            $zip->close();
            throw new RuntimeException("Could not extract content.xml from ODT File");
        }

        // Process styles.xml
        $stylesXML = $zip->getFromName("styles.xml");
        if($stylesXML === false){
            $this->conversionWarnings[] = "Could not extract styles.xml from ODT file. Styles may be incomplete.";
        }

        // Process meta.xml if needed
        $metaXML = null;
        if ($options['extractMetadata']) {
            $metaXML = $zip->getFromName("meta.xml");
            if($metaXML === false){
                $this->conversionWarnings[] = "Could not extract meta.xml from ODT file. Metadata will be missing.";
            }
        }

        // Process images
        $images = [];
        try {
            // Convert ODT XML to HTML
            $htmlContent = $this->htmlHandler->handle($contentXML, $zip, $images);

            // Process CSS
            if ($stylesXML) {
                $xml = new SimpleXMLElement($stylesXML);
                $cssArray = [];
                $this->cssHandler->handle($xml, $cssArray);
                $css = implode("\n", array_unique($cssArray));
            } else {
                $css = "/* No styles found */";
            }

            // Combine HTML and CSS
            $finalHTML = $this->injectCSSIntoHTML($htmlContent, $css, $options['cssStrategy']);

            // Process metadata if available
            if ($metaXML && $options['extractMetadata']) {
                $finalHTML = $this->injectMetadata($finalHTML, $metaXML);
            }

            // Apply sanitization if requested
            if ($options['sanitizeHTML']) {
                $finalHTML = $this->sanitizeHTML($finalHTML);
            }

            // Complete conversion statistics
            $this->conversionStats['end_time'] = microtime(true);
            $this->conversionStats['duration'] = $this->conversionStats['end_time'] - $this->conversionStats['start_time'];
            $this->conversionStats['image_count'] = count($images);
            $this->conversionStats['html_size'] = strlen($finalHTML);

            // Close the zip file
            $zip->close();

            return [
                'html' => $finalHTML,
                'warnings' => $this->conversionWarnings,
                'errors' => $this->conversionErrors,
                'stats' => $this->conversionStats
            ];

        } catch (Exception $e) {
            $this->conversionErrors[] = "Conversion error: " . $e->getMessage();
            $zip->close();
            throw $e;
        }
    }

    /**
     * Injects CSS into HTML based on strategy
     *
     * @param string|array $html HTML content
     * @param string $css CSS content
     * @param string $strategy CSS injection strategy ('embed' or 'external')
     * @return string Processed HTML with CSS
     */
    private function injectCSSIntoHTML($html, string $css, string $strategy = 'embed'): string
    {
        if ($strategy === 'external') {
            // Implementation for external CSS file
            // This would generate a CSS file and link to it
            return $this->injectExternalCSSIntoHTML($html, $css);
        }

        // Default: embed CSS in HTML
        if (is_string($html)) {
            // Fix head tag if missing
            if (!str_contains($html, '<head>')) {
                $html = preg_replace('/<html[^>]*>/', '$0<head><meta charset="UTF-8"><title>Converted Document</title></head>', $html);
            }

            // Insert CSS into existing style tag or create new one
            if (preg_match('/<style[^>]*>(.*?)<\/style>/s', $html)) {
                $html = preg_replace('/<style[^>]*>(.*?)<\/style>/s', "<style>\n$css\n</style>", $html);
            } else {
                $html = preg_replace('/<head>(.*?)<\/head>/s', "<head>$1<style>\n$css\n</style></head>", $html);
            }

            return $html;
        }

        if (is_array($html)) {
            // Handle array of HTML fragments
            $styleFound = false;
            foreach ($html as $key => $value) {
                if (is_string($value) && preg_match('/<style[^>]*>/', $value)) {
                    $html[$key] = preg_replace('/<style[^>]*>(.*?)<\/style>/s', "<style>\n$css\n</style>", $value);
                    $styleFound = true;
                    break;
                }
            }

            if (!$styleFound) {
                foreach ($html as $key => $value) {
                    if (is_string($value) && str_contains($value, '<head>')) {
                        $html[$key] = preg_replace('/<head>(.*?)<\/head>/s', "<head>$1<style>\n$css\n</style></head>", $value);
                        $styleFound = true;
                        break;
                    }
                }
            }

            if (!$styleFound) {
                // If no head or style tag found, prepend to array
                array_unshift($html, "<style>\n$css\n</style>");
            }

            // Join array elements
            return implode("\n", $html);
        }

        // Fallback for unexpected input
        return $html;
    }

    /**
     * Injects CSS as an external file reference
     *
     * @param string|array $html HTML content
     * @param string $css CSS content
     * @return string Processed HTML with CSS link
     */
    private function injectExternalCSSIntoHTML($html, string $css): string
    {
        // Implementation for external CSS
        // This would save CSS to a file and add a link in the HTML

        // For now, just return embedded CSS
        return $this->injectCSSIntoHTML($html, $css, 'embed');
    }

    /**
     * Injects metadata from meta.xml into HTML
     *
     * @param string $html HTML content
     * @param string $metaXML Meta XML content
     * @return string HTML with metadata
     */
    private function injectMetadata(string $html, string $metaXML): string
    {
        try {
            $metaDom = new DOMDocument();
            $metaDom->loadXML($metaXML);
            $metaXPath = new DOMXPath($metaDom);

            // Register namespaces
            $metaXPath->registerNamespace('dc', 'http://purl.org/dc/elements/1.1/');
            $metaXPath->registerNamespace('meta', 'urn:oasis:names:tc:opendocument:xmlns:meta:1.0');

            // Extract metadata
            $metaTags = [];

            // Title
            $title = $metaXPath->evaluate('string(//dc:title)');
            if ($title) {
                $metaTags[] = '<meta name="title" content="' . htmlspecialchars($title) . '">';
                // Also update title tag
                $html = preg_replace('/<title>[^<]*<\/title>/', '<title>' . htmlspecialchars($title) . '</title>', $html);
            }

            // Description
            $description = $metaXPath->evaluate('string(//dc:description)');
            if ($description) {
                $metaTags[] = '<meta name="description" content="' . htmlspecialchars($description) . '">';
            }

            // Subject
            $subject = $metaXPath->evaluate('string(//dc:subject)');
            if ($subject) {
                $metaTags[] = '<meta name="keywords" content="' . htmlspecialchars($subject) . '">';
            }

            // Creator
            $creator = $metaXPath->evaluate('string(//meta:initial-creator)');
            if ($creator) {
                $metaTags[] = '<meta name="author" content="' . htmlspecialchars($creator) . '">';
            }

            // Creation date
            $creationDate = $metaXPath->evaluate('string(//meta:creation-date)');
            if ($creationDate) {
                $metaTags[] = '<meta name="date" content="' . htmlspecialchars($creationDate) . '">';
            }

            // Insert metadata into head
            if (!empty($metaTags)) {
                $metaString = implode("\n    ", $metaTags);
                $html = preg_replace('/<head>(.*?)<\/head>/s', "<head>\$1\n    $metaString\n</head>", $html);
            }

        } catch (Exception $e) {
            $this->conversionWarnings[] = "Failed to process metadata: " . $e->getMessage();
        }

        return $html;
    }

    /**
     * Sanitizes HTML output
     *
     * @param string $html HTML content
     * @return string Sanitized HTML
     */
    private function sanitizeHTML(string $html): string
    {
        // Basic sanitization
        // Remove any remaining ODT-specific tags and attributes
        $html = preg_replace('/<\/?(office|text|style|draw|table|fo|svg|dc|meta|number|presentation|dr3d|math|form|script|ooo|ooow|oooc|dom|xforms|xsd|xsi|rpt|of|xhtml|grddl|tableooo|drawooo|calcext):[^>]*>/i', '', $html);

        // Remove namespaces
        $html = preg_replace('/xmlns:[^=]*="[^"]*"/', '', $html);

        // Fix self-closing tags to be HTML5 compliant
        $html = preg_replace('/<([a-z][a-z0-9]*)[^>]*\/>/i', '<$1 />', $html);

        // Remove empty paragraphs
        $html = preg_replace('/<p[^>]*>\s*<\/p>/i', '', $html);

        return $html;
    }

    /**
     * Resets the conversion state for a new conversion
     */
    private function resetConversionState(): void
    {
        $this->conversionWarnings = [];
        $this->conversionErrors = [];
        $this->conversionStats = [];
    }

    /**
     * Gets the conversion warnings
     *
     * @return array Warnings generated during conversion
     */
    public function getWarnings(): array
    {
        return $this->conversionWarnings;
    }

    /**
     * Gets the conversion errors
     *
     * @return array Errors generated during conversion
     */
    public function getErrors(): array
    {
        return $this->conversionErrors;
    }

    /**
     * Gets the conversion statistics
     *
     * @return array Statistics collected during conversion
     */
    public function getStats(): array
    {
        return $this->conversionStats;
    }
}