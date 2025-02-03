<?php

//Importation de la classe mère
require_once "utils/XMLHandler.php";

class TextFormattingHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Remplacement des balises ODT par du HTML
        $xml = preg_replace('/<text:span[^>]*text:style-name="([^"]+)"[^>]*>(.*?)<\/text:span>/', '<span class="$1">$2</span>', $xml);
        $xml = preg_replace('/<text:p[^>]*>(.*?)<\/text:p>/', '<p>$1</p>', $xml);

        return $xml;
    }
}
