<?php

require_once "utils/CSSHandler.php";

class TableCSSHandler implements CSSHandler
{
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
        $existingTables = [];
        foreach($XML->xpath("//style:default-style[@style:family='table']") as $style){
            $borderModel = (string) ($style->xpath('style:table-properties/@table:border-model')[0] ?? "separate");
            $tableRule = "table { border-collapse: " . ($borderModel === "collapsing" ? "collapse" : "separate") . "; }";
            if (!in_array($tableRule, $existingTables, true)) {
                $css[] = $tableRule;
                $existingTables[] = $tableRule;
            }
        }
        foreach($XML->xpath("//style:style[@style:family='table']") as $style){
            $name = (string) $style["style:name"];
            $border = (string)($style->xpath("style:table-properties/@fo:border")[0] ?? "1px solid black");
            $css[] = ".$name { border: $border; }";
        }
        $this->nextHandler?->handle($XML, $css);
    }
}