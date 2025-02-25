<?php

require_once "utils/CSSHandler.php";
require_once "utils/CSS/FontCSSHandler.php";
require_once "utils/CSS/ParagraphCSSHandler.php";
require_once "utils/CSS/TableCSSHandler.php";

class ODTToCSSConverter{

    private FontCSSHandler $handler;

    /**
     * Le constructeur en question.
     */
    public function __construct()
    {
        $this->handler = new FontCSSHandler();

        $this->handler
            ->setNext(new ParagraphCSSHandler())
            ->setNext(new TableCSSHandler())
            ->setNext(new HeadingStyleHandler())
            ->setNext(new ListCSSHandler())
            ->setNext(new ImageCSSHandler())
            ->setNext(new LinkCSSHandler());
    }

    /**
     * @param string $odtFilePath Le chemin du fichier ODT en question.
     * @throws Exception Au cas où l'instanciation de SimpleXMLElement ne marche pas.
     * @return string le XML convertit en CSS.
     */
    public function convertStylisation(string $odtFilePath): string{
        $zip = new ZipArchive();
        if($zip->open($odtFilePath) !== true){
            $zip->close();

            throw new RuntimeException("Erreur d'ouverture de fichier ODT");
        }

        $contenu = $zip->getFromName("styles.xml");

        if($contenu !== false){
            $xml = new SimpleXMLElement($contenu);
            $css = [];

            $this->handler->handle($xml, $css);

            return implode("\n", $css);
        }

        throw new RuntimeException("Erreur de récupération de styles.xml");
    }
}
