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

class ODTToFullConverter
{
    private DocumentStructureHandler $htmlHandler;
    private FontCSSHandler $cssHandler;

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
        $css = implode("\n", $cssArray);

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
                        $html[$key] = str_replace('<head>', "<head>\n<style>\n$css\n</style>", $value);
                        break;
                    }
                }
            }

            return $html;
        }
        return $html;
    }
}
