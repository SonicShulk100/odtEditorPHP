<?php

//Importations des classes nécessaires pour handlers/html
require_once 'HTMLHandler.php';
require_once 'handlers/html/ImageHTMLHandler.php';
require_once 'handlers/html/ParagraphHTMLHandler.php';
require_once 'handlers/html/StyleHTMLHandler.php';
require_once 'handlers/html/ListHTMLHandler.php';
require_once 'handlers/html/TableHTMLHandler.php';
require_once "handlers/html/TextStyleHTMLHandler.php";
require_once "handlers/html/HeadingHTMLHandler.php";
require_once "handlers/html/DocumentStructureHTMLHandler.php";
require_once "handlers/html/LinkHTMLHandler.php";

//Importations des classes nécessaires pour CSS
require_once "CSSHandler.php";
require_once "handlers/css/FontCSSHandler.php";
require_once "handlers/css/ParagraphCSSHandler.php";
require_once "handlers/css/TableCSSHandler.php";
require_once "handlers/css/HeadingStyleHandler.php";
require_once "handlers/css/ImageCSSHandler.php";
require_once "handlers/css/LinkCSSHandler.php";
require_once "handlers/css/ListCSSHandler.php";

class ODTToFullConverter
{
    private DocumentStructureHTMLHandler $htmlHandler;
    private CSSHandler $cssHandler;

    public function __construct()
    {
        // Configuration de la chaîne de responsabilité pour handlers/html
        $this->htmlHandler = new DocumentStructureHTMLHandler();
        $this->htmlHandler
            ->setNext(new StyleHTMLHandler())
            ->setNext(new HeadingHTMLHandler())
            ->setNext(new ListHTMLHandler())
            ->setNext(new ParagraphHTMLHandler())
            ->setNext(new TableHTMLHandler())
            ->setNext(new ImageHTMLHandler())
            ->setNext(new TextStyleHTMLHandler())
            ->setNext(new LinkHTMLHandler());

        // Configuration de la chaîne de responsabilité pour CSS
        $this->cssHandler = new FontCSSHandler();
        $this->cssHandler
            ->setNext(new ParagraphCSSHandler())
            ->setNext(new TableCSSHandler())
            ->setNext(new HeadingStyleHandler())
            ->setNext(new ListCSSHandler())
            ->setNext(new ImageCSSHandler())
            ->setNext(new LinkCSSHandler());
    }

    /**
     * @throws RunTimeException|Exception
     */
    public function convert(string $odtFilePath): string{
        $zip = new ZipArchive();
        if($zip->open($odtFilePath) !== TRUE){
            $zip->close();
            throw new RuntimeException("Could not open ODT file.");
        }

        $contentXML = $zip->getFromName("content.xml");
        if($contentXML === false){
            $zip->close();
            throw new RuntimeException("Could not extract content.xml from ODT File");
        }

        $images = [];
        $htmlContent = $this->htmlHandler->handle($contentXML, $zip, $images);

        $stylesXML = $zip->getFromName("styles.xml");
        if($stylesXML === false){
            $zip->close();
            throw new RuntimeException("Could not extract styles.xml from ODT File");
        }

        $xml = new SimpleXMLElement($stylesXML);
        $cssArray = [];
        $this->cssHandler->handle($xml, $cssArray);
        $css = implode("\n", array_unique($cssArray)); // Ensure unique CSS rules

        return $this->injectCSSIntoHTML($htmlContent, $css);
    }

    private function injectCSSIntoHTML($html, string $css){
        if(is_string($html)){
            $pattern = '/<style>(.*?)<\/style>/s';
            if(preg_match($pattern, $html)){
                return preg_replace($pattern, "<style>\n$css\n</style>", $html);
            }

            return preg_replace('/<head>(.*?)<\/head>/s', "<head>$1<style>\n$css\n</style></head>", $html);
        }

        if(is_array($html)){
            $styleFound = false;
            foreach($html as $key => $value){
                if(is_string($value) && str_contains($value, '<style>') && str_contains($value, '</style>')){
                    $html[$key] = str_replace('<style></style>', "<style>\n$css\n</style>", $value);
                    $styleFound = true;
                    break;
                }
            }

            if(!$styleFound){
                foreach($html as $key => $value){
                    if(is_string($value) && str_contains($value, "<head>")){
                        $html[$key] = str_replace('<head></head>', "<head>\n<style>\n$css\n</style><title>Converted Document</title>", $value);
                        break;
                    }
                }
            }

            return $html;
        }
        return $html;
    }
}
