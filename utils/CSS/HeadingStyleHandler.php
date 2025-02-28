<?php

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
        foreach($XML->xpath('//style:style[@style:family="paragraph"]') as $style){
            $name = (string) $style["style:name"];
            $fontSize = (string) ($style->xpath("style:text/@fo:font-size")[1] ?? "inherit");
            if(str_contains(strtolower($name), "heading")){
                $css[] = ".$name { font-size: $fontSize; font-weight: bold; }";
            }
        }

        $this->nextHandler?->handle($XML, $css);
    }
}
