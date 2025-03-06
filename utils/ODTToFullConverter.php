<?php

require_once 'HTML/ImageHandler.php';

class ODTToFullConverter {
    // Configuration options
    private array $options = [
        'extractImages' => true,
        'imageOutputDir' => 'images/',
        'cleanupTempFiles' => true,
        'preserveStyles' => true
    ];

    // Style mapping for ODT to CSS
    private array $styleMapping = [];

    // Temporary storage for extracted files
    private string $tempDir;

    // Image handling
    private int $imageCounter = 0;
    private array $extractedImages = [];

    /**
     * Constructor with optional configuration
     */
    public function __construct(array $options = []) {
        $this->options = array_merge($this->options, $options);
        $this->tempDir = sys_get_temp_dir() . '/odt_' . uniqid('', true);

        // Initialize style mapping
        $this->initStyleMapping();
    }

    /**
     * Initialize default style mappings from ODT to CSS
     * @return void
     */
    private function initStyleMapping(): void
    {
        $this->styleMapping = [
            // Text styles
            'text:p' => 'p',
            'text:h' => 'h',
            'text:list' => 'ul',
            'text:list-item' => 'li',
            'text:span' => 'span',

            // Table styles
            'table:table' => 'table',
            'table:table-row' => 'tr',
            'table:table-cell' => 'td',

            // Style properties mapping
            'fo:font-weight' => 'font-weight',
            'fo:font-style' => 'font-style',
            'fo:font-size' => 'font-size',
            'fo:color' => 'color',
            'fo:background-color' => 'background-color',
            'fo:text-align' => 'text-align',
            'fo:margin-left' => 'margin-left',
            'fo:margin-right' => 'margin-right',
            'fo:margin-top' => 'margin-top',
            'fo:margin-bottom' => 'margin-bottom',
            'fo:padding' => 'padding',
            'fo:line-height' => 'line-height',
            'style:text-underline-style' => 'text-decoration'
        ];
    }

    /**
     * Convert ODT file to HTML
     *
     * @param string $odtFilePath Path to the ODT file
     * @return string HTML content with embedded CSS
     * @throws Exception If conversion fails
     */
    public function convert(string $odtFilePath): string {
        if (!file_exists($odtFilePath)) {
            throw new RuntimeException("ODT file not found: $odtFilePath");
        }

        try {
            // Create temp directory for extraction
            if (!is_dir($this->tempDir) && !mkdir($concurrentDirectory = $this->tempDir, 0755, true) && !is_dir($concurrentDirectory)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }

            // Extract ODT (which is a ZIP file)
            $this->extractODT($odtFilePath);

            // Parse content.xml
            $contentXml = $this->loadXmlFile($this->tempDir . '/content.xml');

            // Parse styles.xml
            $stylesXml = $this->loadXmlFile($this->tempDir . '/styles.xml');

            // Process styles from both files
            $stylesArray = $this->processStyles($contentXml, $stylesXml);

            // Convert content to HTML
            $htmlContent = $this->processContent($contentXml);

            // Generate CSS from styles
            $css = $this->generateCSS($stylesArray);

            // Combine HTML and CSS
            $fullHtml = $this->createHtmlDocument($htmlContent, $css);

            // Clean up if needed
            if ($this->options['cleanupTempFiles']) {
                $this->cleanup();
            }

            return $fullHtml;
        } catch (Exception $e) {
            $this->cleanup();
            throw new RuntimeException("ODT conversion failed: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Extract ODT file (ZIP) to temporary directory
     */
    private function extractODT(string $odtFilePath): void {
        $zip = new ZipArchive();
        if ($zip->open($odtFilePath) !== true) {
            throw new RuntimeException("Failed to open ODT file as ZIP archive");
        }

        $zip->extractTo($this->tempDir);
        $zip->close();
    }

    /**
     * Load XML file and return SimpleXMLElement
     * @throws Exception If XML file not found
     * @return SimpleXMLElement the XML element.
     */
    private function loadXmlFile(string $xmlPath): SimpleXMLElement {
        if (!file_exists($xmlPath)) {
            throw new RuntimeException("XML file not found: $xmlPath");
        }

        $content = file_get_contents($xmlPath);
        $content = mb_convert_encoding($content, 'UTF-8', 'auto');

        // Register ODT namespaces
        $xml = new SimpleXMLElement($content);
        $xml->registerXPathNamespace('office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
        $xml->registerXPathNamespace('style', 'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
        $xml->registerXPathNamespace('text', 'urn:oasis:names:tc:opendocument:xmlns:text:1.0');
        $xml->registerXPathNamespace('table', 'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
        $xml->registerXPathNamespace('draw', 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0');
        $xml->registerXPathNamespace('fo', 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0');
        $xml->registerXPathNamespace('xlink', 'http://www.w3.org/1999/xlink');

        return $xml;
    }

    /**
     * Process styles from content.xml and styles.xml
     * @param SimpleXMLElement $contentXml Content XML element
     * @param SimpleXMLElement $stylesXml Styles XML element
     * @return array Array of styles
     */
    private function processStyles(SimpleXMLElement $contentXml, SimpleXMLElement $stylesXml): array {
        $styles = [];

        // Extract automatic styles from content.xml
        $this->extractStyles($contentXml, $styles, 'automatic-styles');

        // Extract styles from styles.xml
        $this->extractStyles($stylesXml, $styles, 'styles');
        $this->extractStyles($stylesXml, $styles, 'automatic-styles');

        return $styles;
    }

    /**
     * Extract styles from XML
     * @param SimpleXMLElement $xml XML element to extract styles from
     * @param array $styles Array to store extracted styles
     * @param string $styleType Type of styles to extract (e.g., 'styles', 'automatic-styles')
     * @throws RuntimeException If style extraction fails
     * @return void Stores extracted styles in the $styles array
     */
    private function extractStyles(SimpleXMLElement $xml, array &$styles, string $styleType): void {
        $styleNodes = $xml->xpath("//office:$styleType/style:style");

        foreach ($styleNodes as $style) {
            $attributes = $style->attributes('style', true);
            $name = (string) ($attributes['name'] ?? null);
            $family = (string) ($attributes['family'] ?? null);

            if (!isset($styles[$name])) {
                $styles[$name] = [
                    'family' => $family,
                    'properties' => []
                ];
            }

            // Process text properties
            $textProps = $style->xpath('./style:text-properties');
            if (!empty($textProps)) {
                foreach ($textProps[0]->attributes() as $namespace => $attrs) {
                    foreach ($attrs as $name => $value) {
                        $styles[$name]['properties']["$namespace:$name"] = (string)$value;
                    }
                }
            }

            // Process paragraph properties
            $paraProps = $style->xpath('./style:paragraph-properties');
            if (!empty($paraProps)) {
                foreach ($paraProps[0]->attributes() as $namespace => $attrs) {
                    foreach ($attrs as $name => $value) {
                        $styles[$name]['properties']["$namespace:$name"] = (string)$value;
                    }
                }
            }

            // Process table properties
            $tableProps = $style->xpath('./style:table-properties');
            if (!empty($tableProps)) {
                foreach ($tableProps[0]->attributes() as $namespace => $attrs) {
                    foreach ($attrs as $name => $value) {
                        $styles[$name]['properties']["$namespace:$name"] = (string)$value;
                    }
                }
            }
        }
    }

    /**
     * Process content.xml to HTML
     * @param SimpleXMLElement $contentXml Content XML element
     * @return string HTML content
     */
    private function processContent(SimpleXMLElement $contentXml): string {
        $body = $contentXml->xpath('//office:body/office:text')[0];
        $html = '';

        foreach ($body->children() as $element) {
            $html .= $this->processElement($element);
        }

        return $html;
    }

    /**
     * Process XML element to HTML
     * @param SimpleXMLElement $element XML element to process
     * @return string HTML content
     */
    private function processElement(SimpleXMLElement $element): string {
        $tagName = $element->getName();

        return match ($tagName) {
            'p' => $this->processParagraph($element),
            'h' => $this->processHeading($element),
            'list' => $this->processList($element),
            'table' => $this->processTable($element),
            'frame' => $this->processFrame($element),
            default => $this->processInlineElement($element),
        };
    }

    /**
     * Process paragraph element
     */
    private function processParagraph(SimpleXMLElement $element): string {
        $style = '';
        if (isset($element['style-name'])) {
            $styleName = (string)$element['style-name'];
            $style = " class=\"$styleName\"";
        }

        $content = $this->getElementContent($element);
        return "<p$style>$content</p>\n";
    }

    /**
     * Process heading element
     */
    private function processHeading(SimpleXMLElement $element): string {
        $level = isset($element['outline-level']) ? (int)$element['outline-level'] : 1;
        $level = min(max($level, 1), 6); // Ensure level is between 1 and 6

        $style = '';
        if (isset($element['style-name'])) {
            $styleName = (string)$element['style-name'];
            $style = " class=\"$styleName\"";
        }

        $content = $this->getElementContent($element);
        return "<h$level$style>$content</h$level>\n";
    }

    /**
     * Process list element
     */
    private function processList(SimpleXMLElement $element): string {
        $style = '';
        if (isset($element['style-name'])) {
            $styleName = (string)$element['style-name'];
            $style = " class=\"$styleName\"";
        }

        $isOrdered = false;
        if (isset($element['type'])) {
            $isOrdered = ((string)$element['type'] === 'ordered');
        }

        $tag = $isOrdered ? 'ol' : 'ul';
        $html = "<$tag$style>\n";

        foreach ($element->children() as $item) {
            if ($item->getName() === 'list-item') {
                $html .= "<li>" . $this->getElementContent($item) . "</li>\n";
            }
        }

        $html .= "</$tag>\n";
        return $html;
    }

    /**
     * Process table element
     */
    private function processTable(SimpleXMLElement $element): string {
        $style = '';
        if (isset($element['style-name'])) {
            $styleName = (string)$element['style-name'];
            $style = " class=\"$styleName\"";
        }

        $html = "<table$style>\n";

        foreach ($element->children() as $row) {
            if ($row->getName() === 'table-row') {
                $html .= "<tr>\n";

                foreach ($row->children() as $cell) {
                    if ($cell->getName() === 'table-cell') {
                        $cellStyle = '';
                        if (isset($cell['style-name'])) {
                            $cellStyleName = (string)$cell['style-name'];
                            $cellStyle = " class=\"$cellStyleName\"";
                        }

                        $html .= "<td$cellStyle>";
                        $html .= $this->getElementContent($cell);
                        $html .= "</td>\n";
                    }
                }

                $html .= "</tr>\n";
            }
        }

        $html .= "</table>\n";
        return $html;
    }

    /**
     * Process frame element (images, etc.)
     */
    private function processFrame(SimpleXMLElement $element): string {
        $html = '';

        foreach ($element->children() as $child) {
            if ($child->getName() === 'image') {
                $html .= $this->processImage($child);
            }
        }

        return $html;
    }

    /**
     * Process image element
     */
    private function processImage(SimpleXMLElement $element): string {
        if (!$this->options['extractImages']) {
            return '<!-- Image extraction disabled -->';
        }

        $href = (string)$element->attributes('xlink', true)['href'];

        // Check if the image reference is valid
        if (empty($href) || !preg_match('/^Pictures\//', $href)) {
            return '<!-- Invalid image reference -->';
        }

        // Extract image
        $imagePath = $this->tempDir . '/' . $href;
        $imageFileName = 'image_' . (++$this->imageCounter) . '_' . basename($href);
        $outputPath = $this->options['imageOutputDir'] . $imageFileName;

        // Ensure output directory exists
        if (!is_dir(dirname($outputPath)) && !mkdir($concurrentDirectory = dirname($outputPath), 0755, true) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        // Copy image file
        if (file_exists($imagePath)) {
            copy($imagePath, $outputPath);
            $this->extractedImages[] = $outputPath;

            // Get width and height if available
            $width = '';
            $height = '';
            if (isset($element['width'])) {
                $width = " width=\"" . $element['width'] . "\"";
            }
            if (isset($element['height'])) {
                $height = " height=\"" . $element['height'] . "\"";
            }

            return "<img src=\"$outputPath\"$width$height alt=\"\">";
        }

        return '<!-- Image file not found -->';
    }

    /**
     * Process inline element (span, etc.)
     */
    private function processInlineElement(SimpleXMLElement $element): string {
        $tagName = $element->getName();

        switch ($tagName) {
            case 'span':
                $style = '';
                if (isset($element['style-name'])) {
                    $styleName = (string)$element['style-name'];
                    $style = " class=\"$styleName\"";
                }

                return "<span$style>" . $this->getElementContent($element) . "</span>";

            case 'line-break':
                return "<br>";

            case 'tab':
                return "&#9;"; // Tab character

            case 'hyperlink':
                $href = (string)$element->attributes('xlink', true)['href'];
                return "<a href=\"$href\">" . $this->getElementContent($element) . "</a>";

            default:
                // Process text content
                return $this->getTextContent($element);
        }
    }

    /**
     * Get element content including child elements
     */
    private function getElementContent(SimpleXMLElement $element): string {
        $content = '';

        foreach ($element->children() as $child) {
            $content .= $this->processElement($child);
        }

        // If there are no child elements, get text content
        if (empty($content)) {
            $content = $this->getTextContent($element);
        }

        return $content;
    }

    /**
     * Get text content of an element
     */
    private function getTextContent(SimpleXMLElement $element): string {
        $text = (string)$element;
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate CSS from styles array
     */
    private function generateCSS(array $styles): string {
        $css = '';

        foreach ($styles as $name => $style) {
            $css .= ".$name {\n";

            foreach ($style['properties'] as $property => $value) {
                if (isset($this->styleMapping[$property])) {
                    $cssProperty = $this->styleMapping[$property];

                    // Handle special cases
                    if ($property === 'style:text-underline-style' && $value !== 'none') {
                        $value = 'underline';
                    }

                    $css .= "    $cssProperty: $value;\n";
                }
            }

            $css .= "}\n\n";
        }

        // Add responsive styles for tables and images
        $css .= "@media (max-width: 900px) { 
    img { 
       max-width: 100%;
       height: auto;
    }
    
    .table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    td, th {
        padding: 8px;
        text-align: left;
        border: 1px solid #ddd;
    }
}\n";

        return $css;
    }

    /**
     * Create complete HTML document with embedded CSS
     */
    private function createHtmlDocument(string $htmlContent, string $css): string {
        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Converted Document</title>
    <style>
' . $css . '
    </style>
</head>
<body>
' . $htmlContent . '
</body>
</html>';
    }

    /**
     * Clean up temporary files
     * @return void
     */
    private function cleanup(): void {
        if (is_dir($this->tempDir)) {
            $this->recursiveRemoveDir($this->tempDir);
        }
    }

    /**
     * Recursively remove directory and its contents
     * @param string $dir Directory path
     * @return void
     */
    private function recursiveRemoveDir(string $dir): void {
        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $path = "$dir/$file";

            if (is_dir($path)) {
                $this->recursiveRemoveDir($path);
            } else {
                unlink($path);
            }
        }

        rmdir($dir);
    }
}
