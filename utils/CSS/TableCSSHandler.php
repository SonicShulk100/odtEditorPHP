<?php

require_once "utils/CSSHandler.php";

class TableCSSHandler implements CSSHandler
{
    private ?CSSHandler $nextHandler = null;

    public function setNext(CSSHandler $handler): CSSHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(SimpleXMLElement $XML, array &$css): void
    {
        $existingTables = [];

        // Handle default table properties
        foreach ($XML->xpath("//style:default-style[@style:family='table']") as $style) {
            $borderModel = (string) ($style->xpath('style:table-properties/@table:border-model')[0] ?? "separate");
            $tableRule = "table { border-collapse: " . ($borderModel === "collapsing" ? "collapse" : "separate") . "; }";
            if (!in_array($tableRule, $existingTables, true)) {
                $css[] = $tableRule;
                $existingTables[] = $tableRule;
            }
        }

        // Handle specific table styles
        foreach ($XML->xpath("//style:style[@style:family='table']") as $style) {
            $name = (string) $style["style:name"];
            $border = (string) ($style->xpath("style:table-properties/@fo:border")[0] ?? "1px solid black");
            $css[] = ".$name { border: $border; }";
        }

        // Handle table row properties
        foreach ($XML->xpath("//style:default-style[@style:family='table-row']") as $style) {
            $keepTogether = (string) ($style->xpath("style:table-row-properties/@fo:keep-together")[0] ?? "auto");
            $css[] = "tr { page-break-inside: " . ($keepTogether === "always" ? "avoid" : "auto") . "; }";
        }

        $this->nextHandler?->handle($XML, $css);
    }
}
