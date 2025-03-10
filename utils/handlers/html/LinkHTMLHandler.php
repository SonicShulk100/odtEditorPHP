<?php

class LinkHTMLHandler extends HTMLHandler{
    public function handle($content, ZipArchive $zip, &$images)
    {
        $pattern = '/<text:a xlink:gref="([^"]*)">(.*?)<\/text:a>/s';

        $replacement = '<a href="$1">$2</a>';

        $content = preg_replace($pattern, $replacement, $content);

        return parent::handle($content, $zip, $images);
    }
}