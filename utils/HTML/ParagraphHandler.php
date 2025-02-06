<?php

//Importation de la classe mère.
require_once 'utils/Handler.php';

class ParagraphHandler extends Handler {
    public function handle($content, $zip, &$images) {
        // Handle paragraph conversion
        // Convert paragraphs from the ODT content to HTML
        $content = preg_replace('/<text:p[^>]*>(.*?)<\/text:p>/', '<p>$1</p>', $content);

        //Appel de la méthode handle de la classe mère.
        return parent::handle($content, $zip, $images) ?? $content;
    }
}
