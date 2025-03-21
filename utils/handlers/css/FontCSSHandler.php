<?php

require_once "utils/handlers/CSSHandler.php";

class FontCSSHandler implements CSSHandler
{
    private ?CSSHandler $nextHandler = null;


    /**
     * @inheritDoc
     */
    #[Override] public function setNext(CSSHandler $handler): CSSHandler
    {
        $this->nextHandler = $handler;
        return $this->nextHandler;
    }

    /**
     * @inheritDoc
     */
    #[Override] public function handle(SimpleXMLElement $XML, array &$css): void
    {
        $existingFonts = [];

        foreach($XML->xpath("//style:font-face") as $font){
            $name = (string) $font["style:name"];
            $family = (string) $font["svg:font-family"];
            $fontRule = "@font-face { font-family: '$family'; src: local($name); }";

            if(!in_array($fontRule, $existingFonts, true)){
                $existingFonts[] = $fontRule;
                $css[] = $fontRule;
            }
        }

        $this->nextHandler?->handle($XML, $css);
    }
}