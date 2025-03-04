<?php

require_once "utils/CSSHandler.php";

/**
 * @inheritDoc
 */
class ParagraphColorCSSHandler implements CSSHandler
{
    private ?CSSHandler $nextHandler = null;

    /**
     * @inheritDoc
     */
    #[Override] public function setNext(CSSHandler $handler): CSSHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    /**
     * @inheritDoc
     */
    #[Override] public function handle(SimpleXMLElement $XML, array &$css): void
    {
        foreach($XML->xpath("//style:style") as $style){
            if(isset($style->{"style:text-properties"})){
                $color = (string) $style->{"style:text-properties"}->attributes("fo", true)->{"color"};
                if($color){
                    $styleName = (string) $style->attributes("style", true)->{"name"};
                    $css[] = ".$styleName{ color: $color; }";
                }
            }
        }

        $this->nextHandler?->handle($XML, $css);
    }
}