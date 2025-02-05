<?php

require_once "utils/AbstractHandler.php";

class StructuralConversionHandler extends AbstractHandler {
    public function handle(string $xml): string {
        // Convert ODT XML elements to HTML equivalents
        $xml = str_replace(array('<text:h', '</text:h>', '<text:p', '</text:p>'), array('<h1>', '</h1>', '<p>', '</p>'), $xml);
        return parent::handle($xml);
    }
}
