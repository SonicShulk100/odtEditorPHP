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
        foreach($XML->xpath("//text:list-style/text:list-level-style-bullet") as $list){
            $level = (int) $list["text:level"];
            $bulletChar = (string) ($list["text:bullet-char"] ?? '•');
            $css[] = "ul li:nth-child($level) { list-style-type: $bulletChar; }";
        }

        foreach($XML->xpath("//text:list-style/text:list-level-style-number") as $list){
            $level = (int) $list["text:level"];
            $css[] = "ol li:nth-child($level) { list-style-type: decimal; }";
        }
        $this->nextHandler?->handle($XML, $css);
    }
}
