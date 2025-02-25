<?php

require_once "utils/CSS";

class LinkCSSHandler implements CSSHandler{
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
        foreach($XML->xpath('//style:style[@style:family="text"]') as $style){
            $name = (string) $style["style:name"];
            $color = (string) ($style->xpath("style:text-properties/@fo:color")[0] ?? "#0000FF");
            $css[] = "a { color: $color; text-decoration: underline; }";
        }

        $this->nextHandler?->handle($XML, $css);
    }
}