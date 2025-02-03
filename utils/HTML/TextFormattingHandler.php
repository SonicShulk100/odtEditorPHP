<?php

require_once "utils/XMLHandler.php";

class TextFormattingHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Traitement du formatage du texte (gras, italique, souligné, etc.)
        return preg_replace('/<text:span text:style-name="([^"]+)">(.+?)<\/text:span>/', '<span class="$1">$2</span>', $xml);
    }
}
