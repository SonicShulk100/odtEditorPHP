<?php

require_once "utils/HTML/MetadataHandler.php";
require_once "utils/HTML/StyleHandler.php";
require_once "utils/HTML/ParagraphHandler.php";
require_once "utils/HTML/ListHandler.php";
require_once "utils/HTML/TableHandler.php";
require_once "utils/HTML/ImageHandler.php";
require_once "utils/HTML/TextFormattingHandler.php";
require_once "utils/HTML/PageLayoutHandler.php";
require_once "utils/HTML/FontHandler.php";
require_once "utils/HTML/GlobalXMLHandler.php";
require_once "utils/HTML/TitleHandler.php";
require_once "utils/HTML/StyleElementHandler.php";
require_once "utils/HTML/ParagraphElementHandler.php";

class ODTToHTMLConverter
{
    private ZipArchive $zip;

    public function __construct(string $odtPath)
    {
        $this->zip = new ZipArchive();
        if ($this->zip->open($odtPath) !== true) {
            throw new RuntimeException("Impossible d'ouvrir le fichier ODT");
        }
    }

    public function convert(): string
    {
        $contentXml = $this->zip->getFromName('content.xml');
        if (!$contentXml) {
            throw new RuntimeException("Impossible de charger le fichier XML");
        }

        // Création des handlers
        $metadataHandler = new MetadataHandler();
        $styleHandler = new StyleHandler();
        $fontHandler = new FontHandler();
        $styleElementHandler = new StyleElementHandler();
        $titleHandler = new TitleHandler();
        $paragraphElementHandler = new ParagraphElementHandler();
        $paragraphHandler = new ParagraphHandler();
        $listHandler = new ListHandler();
        $tableHandler = new TableHandler();
        $imageHandler = new ImageHandler($this->zip);
        $textFormattingHandler = new TextFormattingHandler();
        $pageLayoutHandler = new PageLayoutHandler();
        $globalHandler = new GlobalXMLHandler();

        // Définition de l'ordre de la chaîne de responsabilité
        $metadataHandler->setNextHandler($styleHandler);
        $styleHandler->setNextHandler($fontHandler);
        $fontHandler->setNextHandler($styleElementHandler);
        $styleElementHandler->setNextHandler($titleHandler);
        $titleHandler->setNextHandler($paragraphElementHandler);
        $paragraphElementHandler->setNextHandler($paragraphHandler);
        $paragraphHandler->setNextHandler($listHandler);
        $listHandler->setNextHandler($tableHandler);
        $tableHandler->setNextHandler($imageHandler);
        $imageHandler->setNextHandler($textFormattingHandler);
        $textFormattingHandler->setNextHandler($pageLayoutHandler);
        $pageLayoutHandler->setNextHandler($globalHandler);

        // Exécution de la chaîne de responsabilité
        $htmlContent = $metadataHandler->handle($contentXml);

        $this->zip->close();
        return $htmlContent;
    }
}
