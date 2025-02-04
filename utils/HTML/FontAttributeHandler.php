<?php

require_once "utils/XMLHandler.php";

class FontAttributeHandler extends BaseHandler {

    //Liste des attributs de police
    private const array FONT_ATTRIBUTES = [
        'fo:font-size',
        'fo:font-weight',
        'svg:font-family',
        'style:font-name-asian',
        'style:font-size-asian'
    ];

    /**
     * Convertit les attributs de police en CSS
     * @param string $attributeName le nom de l'attribut
     * @param string $value la valeur de l'attribut
     * @return string le code CSS généré
     */
    private function convertFontAttribute(string $attributeName, string $value): string {
        // Implementation for converting font attributes
        return match ($attributeName) {
            'fo:font-size', 'style:font-size-asian' => "font-size: {$value};",
            'fo:font-weight' => "font-weight: {$value};",
            'svg:font-family', 'style:font-name-asian' => "font-family: {$value};",
            default => '',
        };
    }


    #[\Override] public function handle(DOMElement $element): ?string
    {
        // TODO: Implement handle() method.
        return "";
    }
}
