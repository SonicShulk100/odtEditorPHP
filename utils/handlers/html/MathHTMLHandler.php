<?php

class MathHTMLHandler extends HTMLHandler {
    public function handle($content, ZipArchive $zip, &$images): string
    {
        $pattern = '/<text:math>(.*?)<\/text:math>/s';
        $replacement = '<math>$1</math>';

        $content = preg_replace($pattern, $replacement, $content);

        return parent::handle($content, $zip, $images);
    }
}