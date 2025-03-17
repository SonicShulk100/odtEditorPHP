<?php

require_once "utils/HTMLHandler.php";

class HeadingHTMLHandler extends HTMLHandler{
    public function handle($content, ZipArchive $zip, &$images): string
    {
        $headingStyle = [
            "P3" => "h1",
            "P4" => "h2"
        ];

        foreach($headingStyle as $style => $tag){
            $pattern = '/<text:p text:style-name="' . $style . '">(.*?)<\/text:p>/s';
            $replacement = "<$tag>$1</$tag>";

            $content = preg_replace($pattern, $replacement, $content);
        }

        return parent::handle($content, $zip, $images);
    }
}