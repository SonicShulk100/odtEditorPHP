<?php

class ListHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Remplacement des listes ordonnées et non ordonnées
        $xml = preg_replace('/<text:list[^>]*text:style-name="([^"]+)"[^>]*>/', '<ul class="$1">', $xml);
        $xml = preg_replace('/<text:list-item[^>]*>/', '<li>', $xml);
        $xml = preg_replace('/<\/text:list-item>/', '</li>', $xml);
        $xml = preg_replace('/<\/text:list>/', '</ul>', $xml);

        return $xml;
    }
}
