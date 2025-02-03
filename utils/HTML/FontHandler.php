<?php

//Importation de la classe mère
require_once "utils/XMLHandler.php";

class FontHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        preg_match_all('/<style:font-face style:name="([^"]+)"[^>]*svg:font-family="([^"]+)"\/>/', $xml, $matches);
        $fontStyles = [];
        foreach ($matches[1] as $key => $fontName) {
            $fontStyles[$fontName] = $matches[2][$key];
        }
        return $xml;
    }
}
