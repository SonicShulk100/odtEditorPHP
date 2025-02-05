<?php

require_once "utils/AbstractHandler.php";

class ListHandler extends AbstractHandler
{
    public function handle(string $xml): string
    {
        // Identify ordered vs unordered lists based on attributes
        $xml = preg_replace('/<text:list text:style-name=".*numbered.*">/', '<ol>', $xml);
        $xml = preg_replace('/<text:list(?! text:style-name=".*numbered.*")>/', '<ul>', $xml);

        // Convert list items
        // Close list tags
        $xml = str_replace(array('<text:list-item>', '</text:list-item>', '</text:list>', '</text:list>'), array('<li>', '</li>', '</ul>', '</ol>'), $xml);

        return parent::handle($xml);
    }
}
