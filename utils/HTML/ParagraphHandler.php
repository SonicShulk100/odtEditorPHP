<?php

require_once "utils/XMLHandler.php";

class ParagraphHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Traitement des paragraphes
        $xml = preg_replace('/<text:p text:style-name="([^"]+)">(.+?)<\/text:p>/', '<p class="$1">$2</p>', $xml);
        return $xml;
    }
}
