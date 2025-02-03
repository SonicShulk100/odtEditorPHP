<?php

//Importation de la classe mère
require_once "utils/XMLHandler.php";

class TitleHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Traitement des titres
        return preg_replace('/<text:h text:style-name="([^"]+)" text:outline-level="(\d+)">(.+?)<\/text:h>/', '<h$2 class="$1">$3</h$2>', $xml);
    }
}
