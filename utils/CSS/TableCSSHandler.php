<?php

require_once "utils/CSSHandler.php";

class TableCSSHandler implements CSSHandler
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
        foreach($XML->xpath("//style:default-style[@style:family='table']") as $style){
            $borderModel = (string) ($style->xpath('style:table-properties/@table:border-model')[0] ?? "separate");
            $css[] = "table { border-collapse: " . ($borderModel === "collapsing" ? "collapse" : "separate") . "; }";
        }

        $this->nextHandler?->handle($XML, $css);
    }
}