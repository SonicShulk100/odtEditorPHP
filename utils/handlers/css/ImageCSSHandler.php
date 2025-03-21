<?php

require_once "utils/handlers/CSSHandler.php";

class ImageCSSHandler implements CSSHandler
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
        $existingImages = [];
        foreach($XML->xpath("//style:default-style[@style:family='graphic']/style:graphic-properties") as $style){
            $border = (string) ($style["style:border"] ?? "transparent");
            $shadowX = (string) ($style["fo:shadow-x"] ?? "0px");
            $shadowY = (string) ($style["fo:shadow-y"] ?? "0px");

            $imageRule = "img { border: $border; box-shadow: $shadowX $shadowY 5px black; }";

            if(!in_array($imageRule, $existingImages, true)){
                $existingImages[] = $imageRule;
                $css[] = $imageRule;
            }
        }

        $this->nextHandler?->handle($XML, $css);
    }
}