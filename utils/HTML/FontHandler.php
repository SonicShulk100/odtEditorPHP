<?php

//Importation de la classe mère
require_once "utils/XMLHandler.php";

class FontHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Traitement des polices
        return preg_replace('/<style:font-face style:name="([^"]+)" svg:font-family="([^"]+)" \/>/', '<style>.font-$1 { font-family: $2; }</style>', $xml);
    }
}
