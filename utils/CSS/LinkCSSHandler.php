<?php

require_once "utils/CSSHandler.php";

class LinkCSSHandler implements CSSHandler {
    private ?CSSHandler $nextHandler = null;

    public function setNext(CSSHandler $handler): CSSHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(SimpleXMLElement $XML, array &$css): void
    {
        foreach ($XML->xpath('//style:style[@style:family="text"]') as $style) {
            $name = (string) $style["style:name"];
            $color = (string) ($style->xpath("style:text-properties/@fo:color")[0] ?? "#0000FF");
            $css[] = ".$name a { color: $color; text-decoration: underline; }";
            $css[] = ".$name a:hover { color: darken($color, 20%); text-decoration: none; }";
        }
        $this->nextHandler?->handle($XML, $css);
    }
}

