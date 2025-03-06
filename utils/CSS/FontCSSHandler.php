<?php

require_once "utils/CSSHandler.php";

/**
 * @inheritDoc
 */
class FontCSSHandler implements CSSHandler {
    private ?CSSHandler $nextHandler = null;

    public function setNext(CSSHandler $handler): CSSHandler {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(SimpleXMLElement $XML, array &$css): void {
        foreach($XML->xpath("//style:font-face") as $font) {
            $name = (string) $font["style:name"];
            $family = (string) $font["svg:font-family"];
            if (!empty($name) && !empty($family)) {
                $css[] = "@font-face { font-family: '$name'; src: local('$family'); }";
            }
        }
        $this->nextHandler?->handle($XML, $css);
    }
}
