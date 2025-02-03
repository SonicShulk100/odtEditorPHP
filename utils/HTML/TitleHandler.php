<?php

//Importation de la classe mère
require_once "utils/XMLHandler.php";

class TitleHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        return preg_replace('/<text:h text:outline-level="([0-9])">(.+?)<\/text:h>/', '<h$1>$2</h$1>', $xml);
    }
}
