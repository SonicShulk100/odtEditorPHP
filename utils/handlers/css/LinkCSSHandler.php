<?php

require_once "utils/CSSHandler.php";

class LinkCSSHandler implements CSSHandler{
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
        $existingLinks = [];
        foreach($XML->xpath('//style:style[@style:family="text"]') as $style){
            $name = (string) $style["style:name"];
            $color = (string) ($style->xpath("style:text-properties/@fo:color")[0] ?? "#0000FF");
            $linkRule = "a { color: $color; text-decoration: underline; }";
            if (!in_array($linkRule, $existingLinks, true)) {
                $css[] = $linkRule;
                $existingLinks[] = $linkRule;
            }
        }
        $this->nextHandler?->handle($XML, $css);
    }
}