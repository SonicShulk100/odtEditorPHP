<?php

require_once "utils/CSSHandler.php";

require_once "utils/CSSHandler.php";

class HeadingStyleHandler implements CSSHandler{
    private ?CSSHandler $nextHandler = null;

    /**
     * @inheritDoc
     */
    #[Override]
    public function setNext(CSSHandler $handler): CSSHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function handle(SimpleXMLElement $XML, array &$css): void
    {
        $existingHeadings = [];
        foreach($XML->xpath('//style:style[@style:family="paragraph"]') as $style){
            $name = (string) $style["style:name"];
            $fontSize = (string) ($style->xpath("style:text-properties/@fo:font-size")[0] ?? "inherit");
            if(str_contains(strtolower($name), "heading")){
                $headingRule = ".$name { font-size: $fontSize; font-weight: bold; }";
                if (!in_array($headingRule, $existingHeadings, true)) {
                    $css[] = $headingRule;
                    $existingHeadings[] = $headingRule;
                }
            }
        }
        $this->nextHandler?->handle($XML, $css);
    }
}
