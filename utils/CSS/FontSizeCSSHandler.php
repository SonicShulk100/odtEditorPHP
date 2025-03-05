<?php

require_once "utils/CSSHandler.php";

class FontSizeCSSHandler implements CSSHandler
{
    private ?CSSHandler $next = null;

    /**
     * @inheritDoc
     */
    #[Override] public function setNext(CSSHandler $handler): CSSHandler
    {
        $this->next = $handler;
        return $handler;
    }

    /**
     * @inheritDoc
     */
    #[Override] public function handle(SimpleXMLElement $XML, array &$css): void
    {
        foreach($XML->xpath("//style:style") as $style){
            if(isset($style->{
                "style:text-properties"
                })){
                $fontSize = (string) $style->{"style:text-properties"}->attributes("fo", true)->{"font-size"};
                $name = (string) $style->attributes("style", true)->{"name"};
                $css[] = ".$name{ font-size: $fontSize, }";
            }
        }
        $this->next?->handle($XML, $css);
    }
}
