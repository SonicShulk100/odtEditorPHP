<?php

require_once "utils/CSSHandler.php";

class ImageCSSHandler implements CSSHandler
{
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
        $existingImages = [];
        foreach($XML->xpath('//style:default-style[@style:family="graphic"]/style:graphic-properties') as $style){
            $border = (string) ($style["svg:stroke-color"] ?? "transparent");
            $shadowX = (string) ($style["draw:shadow-offset-x"] ?? "0px");
            $shadowY = (string) ($style["draw:shadow-offset-y"] ?? "0px");
            $imageRule = "img { border: 1px solid $border; box-shadow: $shadowX $shadowY gray; }";
            if (!in_array($imageRule, $existingImages, true)) {
                $css[] = $imageRule;
                $existingImages[] = $imageRule;
            }
        }
        $this->nextHandler?->handle($XML, $css);
    }
}
