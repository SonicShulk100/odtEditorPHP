<?php

require_once "utils/HTMLHandler.php";

class DrawingHTMLHandler extends HTMLHandler {
    public function handle($content, ZipArchive $zip, &$images): string
    {
        $pattern = '/<draw:frame draw:name="([^"]*)" draw:style-name="([^"]*)" draw:text-box="true" svg:width="([^"]*)" svg:height="([^"]*)">(.*?)<\/draw:frame>/s';
        $replacement = '<img src="$1" style="width: $2; height: $3;">';

        $content = preg_replace($pattern, $replacement, $content);

        return parent::handle($content, $zip, $images);
    }
}