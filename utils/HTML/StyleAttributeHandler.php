<?php

require_once "utils/BaseHandler.php";

class StyleAttributeHandler extends BaseHandler {

    private const array STYLE_ATTRIBUTES = [
        'style:text-underline-style',
        'style:text-underline-width',
        'style:text-underline-color',
        'style:font-adornments',
        'style:font-pitch',
        'style:font-charset',
        'style:run-through'
    ];

    public function handle(DOMElement $element): ?string {
        foreach ($this::STYLE_ATTRIBUTES as $attributeName) {
            if ($element->hasAttribute($attributeName)) {
                // Convert style attributes to CSS
                $value = $element->getAttribute($attributeName);
                return $this->convertStyleToCSS($attributeName, $value);
            }
        }
        return parent::handle($element);
    }

    private function convertStyleToCSS(string $attributeName, string $value): string {
        // Implementation for converting specific style attributes to CSS
        return match ($attributeName) {
            'style:text-underline-style' => "text-decoration: $value;",
            'style:text-underline-width' => "text-decoration-thickness: $value;",
            'style:text-underline-color' => "text-decoration-color: $value;",
            'style:font-adornments' => "font-variant: $value;",
            'style:font-pitch' => "font-variant-caps: $value;",
            'style:font-charset' => "font-variant-numeric: $value;",
            'style:run-through' => "text-decoration-line: $value;",
            default => '',
        };
    }
}