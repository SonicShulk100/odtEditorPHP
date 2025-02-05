<?php

//Importation des classes nécessaires
require_once "ODTExtractor.php";
require_once "utils/HTML/FormattingHandler.php";
require_once "utils/HTML/HyperlinkHandler.php";
require_once "utils/HTML/ImageHandler.php";
require_once "utils/HTML/ListHandler.php";
require_once "utils/HTML/PreprocessingHandler.php";
require_once "utils/HTML/StructuralConversionHandler.php";
require_once "utils/HTML/TableHandler.php";
require_once "utils/HTML/StyleHandler.php";

class ODTToHTMLConverter
{
    private XMLToHTMLHandler $handlerChain;

    /**
     * Constructeur de la classe.
     */
    public function __construct()
    {
        // Initialize the chain of responsibility
        $preprocessor = new PreprocessingHandler();
        $structure = new StructuralConversionHandler();
        $formatting = new FormattingHandler();
        $hyperlinks = new HyperlinkHandler();
        $images = new ImageHandler();
        $tables = new TableHandler();
        $lists = new ListHandler();
        $styles = new StyleHandler();

        // Set up the handler chain
        $preprocessor
            ->setNext($structure)
            ->setNext($formatting)
            ->setNext($hyperlinks)
            ->setNext($images)
            ->setNext($tables)
            ->setNext($lists)
            ->setNext($styles);

        $this->handlerChain = $preprocessor;
    }

    /**
     * Convertit la structure d'un fichier ODT en HTML en fonction du fichier "content.xml".
     * @param string $odtFilePath le fichier ODT à convertir
     * @return string|null le contenu HTML converti ou NULL en cas d'erreur
     */
    public function convert(string $odtFilePath): ?string
    {
        try {
            // Step 1: Extract content.xml
            $xmlContent = ODTExtractor::extractContentXML($odtFilePath);
            if (!$xmlContent) {
                throw new RuntimeException("Failed to extract content.xml from ODT file.");
            }

            // Step 2: Load XML content into DOMDocument
            $doc = new DOMDocument();
            libxml_use_internal_errors(true);
            if (!$doc->loadXML('<?xml version="1.0" encoding="UTF-8"?><root>' . $xmlContent . '</root>')) {
                error_log("Error: Invalid XML structure.");
                foreach (libxml_get_errors() as $error) {
                    error_log("XML Error: " . $error->message);
                }
                libxml_clear_errors();
                throw new RuntimeException("Invalid XML structure.");
            }

            // Step 3: Convert to HTML using Chain of Responsibility
            return $this->handlerChain->handle($doc->saveXML());

        } catch (Exception $e) {
            error_log("Error during conversion: " . $e->getMessage());
            return "<p>Error converting document: " . htmlentities($e->getMessage()) . "</p>";
        }
    }

}
