<?php

//Importation de la classe mère
require_once "utils/XMLHandler.php";

class GlobalXMLHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Suppression des balises <office:document-content> et autres balises de structure
        $xml = preg_replace('/<office:document-content[^>]*>/', '', $xml);
        $xml = preg_replace('/<\/office:document-content>/', '', $xml);

        // Suppression des balises <office:body> si présent
        $xml = preg_replace('/<office:body>/', '', $xml);
        $xml = preg_replace('/<\/office:body>/', '', $xml);

        // Suppression des métadonnées et autres balises inutiles
        $xml = preg_replace('/<office:automatic-styles[^>]*>.*?<\/office:automatic-styles>/s', '', $xml);
        $xml = preg_replace('/<office:meta[^>]*>.*?<\/office:meta>/s', '', $xml);

        // Transformation des styles globaux en CSS (si nécessaire)
        $xml = preg_replace('/<office:styles[^>]*>.*?<\/office:styles>/s', '', $xml);

        // Ajout d'une enveloppe HTML pour contenir le document
        return "<div class='odt-document'>" . $xml . "</div>";
    }
}
