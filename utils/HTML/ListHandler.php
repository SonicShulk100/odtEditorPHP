<?php

require_once "utils/Handler.php";

class ListHandler extends Handler{
    public function handle($content, ZipArchive $zip, &$images)
    {
        // Process list structures

        // Simple pattern for unordered lists
        $content = preg_replace(
            '/<text:list[^>]*>(.*?)<\/text:list>/s',
            '<ul>$1</ul>',
            $content
        );

        // List items
        $content = preg_replace(
            '/<text:list-item>(.*?)<\/text:list-item>/s',
            '<li>$1</li>',
            $content
        );

        // Clean up list paragraphs
        $content = preg_replace(
            '/<li><text:p[^>]*>(.*?)<\/text:p><\/li>/s',
            '<li>$1</li>',
            $content
        );

        return parent::handle($content, $zip, $images);
    }
}
