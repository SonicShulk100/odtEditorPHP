<?php

require_once "utils/handlers/CSSHandler.php";

class LinkCSSHandler implements CSSHandler
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
        $existingLinks = [];

        foreach($XML->xpath("//style:style[@style:family='text']") as $style){
            $name = (string) $style["style:name"];
            $color = (string) ($style->xpath("style:text-properties/@fo:color")[0] ?? "inherit");
            $textDecoration = (string) ($style->xpath("style:text-properties/@style:text-underline-style")[0] ?? "none");

            $linkRule = ".$name { color: $color; text-decoration: $textDecoration; }";

            if(!in_array($linkRule, $existingLinks, true)){
                $existingLinks[] = $linkRule;
                $css[] = $linkRule;
            }
        }

        $this->nextHandler?->handle($XML, $css);
    }
}