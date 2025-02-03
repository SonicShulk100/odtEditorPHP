<?php

require_once "utils/XMLHandler.php";

class ListHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Traitement des listes non-ordonnées et ordonnées
        $xml = preg_replace('/<text:list text:style-name="([^"]+)">(.+?)<\/text:list>/', '<ul class="$1">$2</ul>', $xml);
        $xml = preg_replace('/<text:list-item>(.+?)<\/text:list-item>/', '<li>$1</li>', $xml);
        return $xml;
    }
}
