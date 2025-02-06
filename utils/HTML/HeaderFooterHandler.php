<?php

require_once 'utils/Handler.php';

class HeaderFooterHandler extends Handler {
    public function handle($content, $zip, &$images) {
        // Handle header and footer conversion
        // Convert headers and footers from the ODT content to HTML
        $content = preg_replace('/<style:header[^>]*>(.*?)<\/style:header>/', '<header>$1</header>', $content);
        $content = preg_replace('/<style:footer[^>]*>(.*?)<\/style:footer>/', '<footer>$1</footer>', $content);

        return parent::handle($content, $zip, $images) ?? $content;
    }
}
