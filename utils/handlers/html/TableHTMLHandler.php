<?php

require_once "utils/HTMLHandler.php";

class TableHTMLHandler extends HTMLHandler{
    public function handle($content, ZipArchive $zip, &$images): string
    {
        // Process table structures

        // Convert tables
        $content = preg_replace(
            '/<table:table[^>]*>(.*?)<\/table:table>/s',
            '<table>$1</table>',
            $content
        );

        // Convert rows
        $content = preg_replace(
            '/<table:table-row[^>]*>(.*?)<\/table:table-row>/s',
            '<tr>$1</tr>',
            $content
        );

        // Convert cells
        $content = preg_replace(
            '/<table:table-cell[^>]*>(.*?)<\/table:table-cell>/s',
            '<td>$1</td>',
            $content
        );

        // Clean up cell paragraphs
        $content = preg_replace(
            '/<td><text:p[^>]*>(.*?)<\/text:p><\/td>/s',
            '<td>$1</td>',
            $content
        );

        $content = preg_replace("/<text:table>(.*?)<\/text:table>/s", "<table>$1</table>", $content);

        $content =preg_replace("/<text:table-row>(.*?)<\/text:table-row>/s", "<tr>$1</tr>", $content);

        $content = preg_replace("/<text:table-cell>(.*?)<\/text:table-cell>/s", "<td>$1</td>", $content);

        return parent::handle($content, $zip, $images);
    }
}