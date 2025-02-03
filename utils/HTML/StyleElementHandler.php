<?php

require_once "utils/XMLHandler.php";

class StyleElementHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Traitement des éléments de style
        $xml = preg_replace('/<style:style[^>]*>(.*?)<\/style:style>/s', '<style>$1</style>', $xml);
        return $xml;
    }
}
