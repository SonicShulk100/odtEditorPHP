<?php

require_once "utils/ODTToHTMLConverter.php";
require_once "utils/ODTToCSSConverter.php";

class ODTToFullConverter
{
    private ODTToHTMLConverter $HTMLConverter;
    private ODTToCSSConverter $CSSConverter;

    public function __construct()
    {
        $this->HTMLConverter = new ODTToHTMLConverter();
        $this->CSSConverter = new ODTToCSSConverter();
    }

    /**
     * Convertit un fichier ODT en HTML.
     * @param string $odtFilePath Le chemin du fichier ODT.
     * @return string Le contenu HTML.
     */
    public function convertToHTML(string $odtFilePath): string {
        return $this->HTMLConverter->convert($odtFilePath);
    }

    /**
     * Convertit un fichier ODT en CSS.
     * @param string $odtFilePath Le chemin du fichier ODT.
     * @return string Le contenu CSS.
     * @throws Exception
     */
    public function convertToCSS(string $odtFilePath): string {
        return $this->CSSConverter->convertStylisation($odtFilePath);
    }
}
