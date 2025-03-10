<?php

require_once "utils/HTMLHandler.php";

class TextStyleHTMLHandler extends HTMLHandler {
    public function handle($content, ZipArchive $zip, &$images) {
        // Process inline text styling

        // Convert text spans with styling
        $pattern = '/<text:span text:style-name="([^"]*)">(.*?)<\/text:span>/s';
        $replacement = '<span class="t-$1">$2</span>';
        $content = preg_replace($pattern, $replacement, $content);

        // Handle bold text
        $content = preg_replace(
            '/<span class="t-(T[23])">(.*?)<\/span>/s',
            '<strong>$2</strong>',
            $content
        );

        // Handle italic text
        $content = preg_replace(
            '/<span class="t-(T1)">(.*?)<\/span>/s',
            '<em>$2</em>',
            $content
        );

        // Pass to next handler
        return parent::handle($content, $zip, $images);
    }
}