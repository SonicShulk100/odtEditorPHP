<?php

require_once "utils/AbstractHandler.php";

class HyperlinkHandler extends AbstractHandler {
    public function handle(string $xml): string {
        // Convert hyperlinks
        $xml = preg_replace('/<text:a\s+xlink:href="(.*?)">(.*?)<\/text:a>/', '<a href="$1">$2</a>', $xml);
        return parent::handle($xml);
    }
}
