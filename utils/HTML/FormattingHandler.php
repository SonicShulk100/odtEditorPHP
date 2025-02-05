<?php

//Importation de la classe mère.
require_once "utils/AbstractHandler.php";

/**
 * Class FormattingHandler
 * Convert text formatting
 * @see AbstractHandler
 */
class FormattingHandler extends AbstractHandler {

    /**
     * Convert text formatting
     * @param string $xml the XML content
     * @return string the HTML content
     */
    public function handle(string $xml): string {
        // Convert text formatting
        $xml = preg_replace('/<text:span\s+fo:font-weight="bold">(.*?)<\/text:span>/', '<strong>$1</strong>', $xml);
        $xml = preg_replace('/<text:span\s+fo:font-style="italic">(.*?)<\/text:span>/', '<em>$1</em>', $xml);
        return parent::handle($xml);
    }
}
