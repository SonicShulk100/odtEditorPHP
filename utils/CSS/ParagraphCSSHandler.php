<?php

require_once "utils/CSSHandler.php";

class ParagraphCSSHandler implements CSSHandler {
    private ?CSSHandler $nextHandler = null;

    public function setNext(CSSHandler $handler): CSSHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(SimpleXMLElement $XML, array &$css): void
    {
        $existingParagraphs = [];

        foreach ($XML->xpath("//style:style[@style:family='paragraph'] | //style:default-style[@style:family='paragraph']") as $style) {
            $name = (string) $style["style:name"] ?: "default-paragraph";
            $marginTop = (string) ($style->xpath("style:paragraph-properties/@fo:margin-top")[0] ?? "0");
            $marginBottom = (string) ($style->xpath("style:paragraph-properties/@fo:margin-bottom")[0] ?? "0");
            $marginLeft = (string) ($style->xpath("style:paragraph-properties/@fo:margin-left")[0] ?? "0");
            $marginRight = (string) ($style->xpath("style:paragraph-properties/@fo:margin-right")[0] ?? "0");
            $lineHeight = (string) ($style->xpath("style:paragraph-properties/@fo:line-height")[0] ?? "normal");
            $textIndent = (string) ($style->xpath("style:paragraph-properties/@fo:text-indent")[0] ?? "0");
            $textAlign = (string) ($style->xpath("style:paragraph-properties/@fo:text-align")[0] ?? "left");

            $paragraphRule = ".$name { 
                margin: $marginTop $marginRight $marginBottom $marginLeft; 
                line-height: $lineHeight;
                text-indent: $textIndent;
                text-align: $textAlign;
            }";

            if (!in_array($paragraphRule, $existingParagraphs, true)) {
                $css[] = $paragraphRule;
                $existingParagraphs[] = $paragraphRule;
            }
        }

        $this->nextHandler?->handle($XML, $css);
    }
}
