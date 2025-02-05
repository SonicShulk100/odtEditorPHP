<?php

require_once "utils/AbstractHandler.php";

class ImageHandler extends AbstractHandler {
    public function handle(string $xml): string {
        // Convert images
        $xml = preg_replace('/<draw:image\s+xlink:href="(.*?)"\/>/', '<img src="$1"  alt=""/>', $xml);
        return parent::handle($xml);
    }
}
