<?php

class TableHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Transformation des tableaux ODT en HTML
        $xml = preg_replace('/<table:table[^>]*>/', '<table>', $xml);
        $xml = preg_replace('/<table:table-row[^>]*>/', '<tr>', $xml);
        $xml = preg_replace('/<table:table-cell[^>]*>/', '<td>', $xml);
        $xml = preg_replace('/<\/table:table-cell>/', '</td>', $xml);
        $xml = preg_replace('/<\/table:table-row>/', '</tr>', $xml);
        $xml = preg_replace('/<\/table:table>/', '</table>', $xml);

        return $xml;
    }
}
