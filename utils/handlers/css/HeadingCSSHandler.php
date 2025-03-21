<?php

require_once "utils/handlers/CSSHandler.php";

class HeadingCSSHandler implements CSSHandler
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
        $existingHeadings = [];
        foreach($XML->xpath("//style:style[@style:family='paragraph']") as $style){
            $name = (string) $style["style:name"];

            $fontSize = (string) ($style->xpath("style:text-properties/@fo:font-size")[0] ?? "inherit");

            $fontWeight = (string) ($style->xpath("style:text-properties/@fo:font-weight")[0] ?? "normal");

            $fontStyle = (string) ($style->xpath("style:text-properties/@fo:font-style")[0] ?? "normal");

            if(str_contains(strtolower($name), "heading")){
                $headingRule = ".$name { font-size: $fontSize; font-weight: $fontWeight; font-style: $fontStyle; }";

                if(!in_array($headingRule, $existingHeadings, true)){
                    $existingHeadings[] = $headingRule;
                    $css[] = $headingRule;
                }
            }
        }

        $this->nextHandler?->handle($XML, $css);
    }
}