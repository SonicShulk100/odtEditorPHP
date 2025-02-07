<?php

require_once "utils/Handler.php";

class RootHandler extends Handler {
    public function handle($content, $zip, &$images) {
        // Handle the root of the XML file
        $content = preg_replace('/<office:document-content[^>]*>/', '<html><body>', $content);
        $content = str_replace('</office:document-content>', '</body></html>', $content);
        $content = preg_replace('/<office:automatic-styles[^>]*>/', '', $content);
        $content = preg_replace('/<\/office:automatic-styles>/', '', $content);

        return parent::handle($content, $zip, $images) ?? $content;
    }
}