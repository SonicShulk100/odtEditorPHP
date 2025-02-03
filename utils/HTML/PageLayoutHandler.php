<?php

//Importation de la classe mère
require_once "utils/XMLHandler.php";

class PageLayoutHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Extraire les marges et les intégrer en tant que styles CSS
        return $xml;
    }
}

