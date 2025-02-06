<?php

require_once 'utils/Handler.php';

class MarginHandler extends Handler {
    public function handle($content, $zip, &$images) {
        // Handle margin conversion
        // Convert page margins from the ODT content to HTML
        $content = preg_replace('/<style:page-layout[^>]+fo:margin-top="([^"]+)"[^>]*fo:margin-bottom="([^"]+)"[^>]*fo:margin-left="([^"]+)"[^>]*fo:margin-right="([^"]+)"[^>]*>/', '<div style="margin:$1 $3 $2 $4;">', $content);

        return parent::handle($content, $zip, $images) ?? $content;
    }
}
