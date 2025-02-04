<?php

require_once "utils/XMLHandler.php";

class MarginAttributeHandler extends BaseHandler {
    //Liste des attributs de Marge
    private const array MARGIN_ATTRIBUTES = [
        'fo:margin-left',
        'fo:margin-right',
        'fo:margin-top',
        'fo:margin-bottom',
        'style:text-indent'
    ];

    public function handle(DOMElement $element): ?string {
        foreach ($this::MARGIN_ATTRIBUTES as $attributeName) {
            if ($element->hasAttribute($attributeName)) {
                $value = $element->getAttribute($attributeName);
                return $this->convertMarginAttribute($attributeName, $value);
            }
        }
        return parent::handle($element);
    }

    /**
     * Convertit les attributs de marge en CSS
     * @param string $attributeName le nom de l'attribut
     * @param string $value la valeur de l'attribut
     * @return string le code CSS généré
     */
    private function convertMarginAttribute(string $attributeName, string $value): string {
        // Implementation for converting margin attributes
        return match ($attributeName) {
            'fo:margin-left' => "margin-left: $value;",
            'fo:margin-right' => "margin-right: $value;",
            'fo:margin-top' => "margin-top: $value;",
            'fo:margin-bottom' => "margin-bottom: $value;",
            'style:text-indent' => "text-indent: $value;",
            default => '',
        };
    }
}
