<?php

require_once "utils/XMLHandler.php";

class PageLayoutHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Traitement des marges de pages, hauts et bas de pages
        // Ajout de styles CSS pour les marges de pages
        return preg_replace('/<style:page-layout-properties[^>]*fo:margin-top="([^"]+)"[^>]*fo:margin-bottom="([^"]+)"[^>]*fo:margin-left="([^"]+)"[^>]*fo:margin-right="([^"]+)"[^>]*>/', '<style>.page { margin-top: $1; margin-bottom: $2; margin-left: $3; margin-right: $4; }</style>', $xml);
    }
}
