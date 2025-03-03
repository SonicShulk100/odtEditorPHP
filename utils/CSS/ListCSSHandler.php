<?php

require_once "utils/CSSHandler.php";

class ListCSSHandler implements CSSHandler{
    private ?CSSHandler $nextHandler = null;

    public function setNext(CSSHandler $handler): CSSHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(SimpleXMLElement $XML, array &$css): void
    {
        $existingLists = [];
        foreach($XML->xpath("//text:list-style/text:list-level-style-bullet") as $list){
            $level = (int) $list["text:level"];
            $bulletChar = (string) ($list["text:bullet-char"] ?? '\u2022');
            $listRule = "ul li:nth-child($level) { list-style-type: \"$bulletChar\"; }";
            if (!in_array($listRule, $existingLists, true)) {
                $css[] = $listRule;
                $existingLists[] = $listRule;
            }
        }

        foreach($XML->xpath("//text:list-style/text:list-level-style-number") as $list){
            $level = (int) $list["text:level"];
            $listRule = "ol li:nth-child($level) { list-style-type: decimal; }";
            if (!in_array($listRule, $existingLists, true)) {
                $css[] = $listRule;
                $existingLists[] = $listRule;
            }
        }
        $this->nextHandler?->handle($XML, $css);
    }
}
