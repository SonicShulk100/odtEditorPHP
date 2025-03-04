<?php

//Importations des classes nécessaires pour HTML
require_once 'Handler.php';
require_once 'HTML/ImageHandler.php';
require_once 'HTML/ParagraphHandler.php';
require_once 'HTML/StyleHandler.php';
require_once 'HTML/ListHandler.php';
require_once 'HTML/TableHandler.php';
require_once "HTML/TextStyleHandler.php";
require_once "HTML/HeadingHandler.php";
require_once "HTML/DocumentStructureHandler.php";
require_once "HTML/LinkHandler.php";

//Importations des classes nécessaires pour CSS
require_once "utils/CSSHandler.php";
require_once "utils/CSS/FontCSSHandler.php";
require_once "utils/CSS/ParagraphCSSHandler.php";
require_once "utils/CSS/TableCSSHandler.php";
require_once "utils/CSS/HeadingStyleHandler.php";
require_once "utils/CSS/ImageCSSHandler.php";
require_once "utils/CSS/LinkCSSHandler.php";
require_once "utils/CSS/ListCSSHandler.php";
require_once "utils/CSS/FontSizeCSSHandler.php";
require_once "utils/CSS/ParagraphColorCSSHandler.php";

class ODTToFullConverter
{
    private DocumentStructureHandler $htmlHandler;
    private CSSHandler $cssHandler;

    public function __construct()
    {
        // Configuration de la chaîne de responsabilité pour HTML
        $this->htmlHandler = new DocumentStructureHandler();
        $this->htmlHandler
            ->setNext(new StyleHandler())
            ->setNext(new HeadingHandler())
            ->setNext(new ListHandler())
            ->setNext(new ParagraphHandler())
            ->setNext(new TableHandler())
            ->setNext(new ImageHandler())
            ->setNext(new TextStyleHandler())
            ->setNext(new LinkHandler());

        // Configuration de la chaîne de responsabilité pour CSS
        $this->cssHandler = new FontCSSHandler();
        $this->cssHandler
            ->setNext(new FontSizeCSSHandler())
            ->setNext(new ParagraphCSSHandler())
            ->setNext(new TableCSSHandler())
            ->setNext(new HeadingStyleHandler())
            ->setNext(new ParagraphColorCSSHandler())
            ->setNext(new ListCSSHandler())
            ->setNext(new ImageCSSHandler())
            ->setNext(new LinkCSSHandler());
    }

    /**
     * @throws RunTimeException|Exception
     */
    public function convert(string $odtFilePath): string
    {
        $zip = new ZipArchive();
        if ($zip->open($odtFilePath) !== TRUE) {
            $zip->close();
            throw new RuntimeException("Could not open ODT file.");
        }

        $contentXML = $zip->getFromName("content.xml");
        if ($contentXML === false) {
            $zip->close();
            throw new RuntimeException("Could not extract content.xml from ODT File");
        }

        $images = [];
        $htmlContent = $this->htmlHandler->handle($contentXML, $zip, $images);

        $stylesXML = $zip->getFromName("styles.xml");
        if ($stylesXML === false) {
            $zip->close();
            throw new RuntimeException("Could not extract styles.xml from ODT File");
        }

        $xml = new SimpleXMLElement($stylesXML);
        $cssArray = [];
        $this->cssHandler->handle($xml, $cssArray);
        $css = implode("\n", array_unique($cssArray)); // Ensure unique CSS rules

        return $this->injectCSSIntoHTML($htmlContent, $css);
    }

    private function injectCSSIntoHTML($html, string $css)
    {
        $styleTag = "<style>\n$css\n</style>";

        if (is_string($html)) {
            // Check if there's an existing <style> tag and replace it
            if (str_contains($html, '<style>')) {
                return preg_replace('/<style>.*?<\/style>/s', $styleTag, $html);
            }

            // Inject CSS into <head> if no <style> tag exists
            return preg_replace('/(<head>)/i', "$1\n$styleTag", $html, 1);
        }

        if (is_array($html)) {
            $styleInjected = false;
            foreach ($html as $key => $value) {
                // Check if there's an existing <style> tag and replace it
                if (is_string($value) && str_contains($value, '<style>')) {
                    $html[$key] = preg_replace('/<style>.*?<\/style>/s', $styleTag, $value);
                    $styleInjected = true;
                    break;
                }
            }

            // Inject CSS into <head> if no <style> tag exists
            if (!$styleInjected) {
                foreach ($html as $key => $value) {
                    if (is_string($value) && str_contains($value, '<head>')) {
                        $html[$key] = preg_replace('/(<head>)/i', "$1\n$styleTag", $value, 1);
                        break;
                    }
                }
            }

            return $html;
        }

        return $html;
    }
}
