<?php

//Importation de la classe mère
require_once "utils/XMLHandler.php";

class GlobalXMLHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // On s'assure que tout le XML est bien formé
        return "<html lang='fr'><body>$xml</body></html>";
    }
}

