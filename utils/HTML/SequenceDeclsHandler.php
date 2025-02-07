<?php

require_once 'utils/Handler.php';

class SequenceDeclsHandler extends Handler{
    public function handle($content, $zip, &$images) {
        // Handle sequence declarations
        // Convert sequence declarations from the ODT content to HTML
        $content = preg_replace('/<text:sequence-decls[^>]*>(.*?)<\/text:sequence-decls>/', '', $content);
        $content = preg_replace('/<text:sequence-decl[^>]*>(.*?)<\/text:sequence-decl>/', '', $content);
        $content = preg_replace('/<text:sequence[^>]*>(.*?)<\/text:sequence>/', '', $content);

        return parent::handle($content, $zip, $images) ?? $content;
    }
}