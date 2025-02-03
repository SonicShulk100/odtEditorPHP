<?php

require_once "utils/XMLHandler.php";

class MetadataHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Traitement des métadonnées
        $metadata = '';
        if (preg_match('/<office:meta>(.*?)<\/office:meta>/s', $xml, $matches)) {
            $metadata = $matches[1];
        }
        return $metadata . $xml;
    }
}
