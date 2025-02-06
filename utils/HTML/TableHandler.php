<?php

//Importing the parent class.
require_once 'utils/Handler.php';

class TableHandler extends Handler {
    public function handle($content, $zip, &$images) {
        // Handle table conversion
        // Convert tables from the ODT content to HTML
        $content = preg_replace('/<table:table[^>]*>(.*?)<\/table:table>/', '<table>$1</table>', $content);
        $content = preg_replace('/<table:table-row[^>]*>(.*?)<\/table:table-row>/', '<tr>$1</tr>', $content);
        $content = preg_replace('/<table:table-cell[^>]*>(.*?)<\/table:table-cell>/', '<td>$1</td>', $content);

        // Call the handle method of the parent class
        return parent::handle($content, $zip, $images) ?? $content;
    }
}
