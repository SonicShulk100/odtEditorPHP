<?php

require_once "utils/XMLHandler.php";

class TableHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Traitement des tableaux
        $xml = preg_replace('/<table:table table:name="([^"]+)">(.+?)<\/table:table>/', '<table class="$1">$2</table>', $xml);
        $xml = preg_replace('/<table:table-row>(.+?)<\/table:table-row>/', '<tr>$1</tr>', $xml);
        $xml = preg_replace('/<table:table-cell>(.+?)<\/table:table-cell>/', '<td>$1</td>', $xml);
        return $xml;
    }
}