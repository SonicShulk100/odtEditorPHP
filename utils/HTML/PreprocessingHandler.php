<?php

//Importation de la classe mère.
require_once "utils/AbstractHandler.php";

class PreprocessingHandler extends AbstractHandler {
    public function handle(string $xml): string {
        // Example: Remove unnecessary namespaces and fix encoding
        $xml = str_replace('office:', '', $xml);
        return parent::handle($xml);
    }
}
