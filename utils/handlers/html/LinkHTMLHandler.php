<?php

require_once "utils/HTMLHandler.php";

class LinkHTMLHandler extends HTMLHandler {
    public function handle($content, ZipArchive $zip, &$images): string
    {
        // Fix the pattern to use href instead of gref
        $pattern = '/<text:a xlink:href="([^"]*)">(.*?)<\/text:a>/s';
        $replacement = '<a href="$1">$2</a>';

        $content = preg_replace($pattern, $replacement, $content);

        return parent::handle($content, $zip, $images);
    }
}