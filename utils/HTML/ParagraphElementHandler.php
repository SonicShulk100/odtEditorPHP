<?php

require_once "utils/XMLHandler.php";

class ParagraphElementHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Traitement des paragraphes
        $xml = preg_replace('/<text:p[^>]*>(.*?)<\/text:p>/s', '<p>$1</p>', $xml);
        return $xml;
    }
}
