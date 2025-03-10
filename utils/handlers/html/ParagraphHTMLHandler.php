<?php

require_once "utils/HTMLHandler.php";

class ParagraphHTMLHandler extends HTMLHandler{
    public function handle($content, ZipArchive $zip, &$images)
    {
        $pattern = '/<text:p text:style-name="([^"]*)">(.*?)<\/text:p>/s';

        $replacement = '<p class="p-$1">$2</p>';

        $content = preg_replace($pattern, $replacement, $content);

        $content = preg_replace(
            "/<text:p>(.*?)<\/text:p>/s",
            "<p>$1</p>",
            $content
        );

        return parent::handle($content, $zip, $images);
    }
}