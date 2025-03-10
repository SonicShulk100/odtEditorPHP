<?php

require_once "utils/CSSHandler.php";

/**
 * @inheritDoc
 */
class FontCSSHandler implements CSSHandler{
    private ?CSSHandler $nextHandler = null;

    #[Override]
    public function setNext(CSSHandler $handler): CSSHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    #[Override]
    public function handle(SimpleXMLElement $XML, array &$css): void
    {
        $existingFonts = [];
        foreach($XML->xpath("//style:font-face") as $font){
            $name = (string) $font["style:name"];
            $family = (string) $font["svg:font-family"];
            $fontRule = "@font-face { font-family: '$name'; src: local('$family'); }";
            if (!in_array($fontRule, $existingFonts, true)) {
                $css[] = $fontRule;
                $existingFonts[] = $fontRule;
            }
        }
        $this->nextHandler?->handle($XML, $css);
    }
}