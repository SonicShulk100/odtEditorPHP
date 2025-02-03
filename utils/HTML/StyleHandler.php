<?php

require_once "utils/XMLHandler.php";

class StyleHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Traitement des styles
        $styles = '';
        if (preg_match('/<office:automatic-styles>(.*?)<\/office:automatic-styles>/s', $xml, $matches)) {
            $styles = $matches[1];
        }
        return $styles . $xml;
    }
}
