<?php

require_once "utils/CSSHandler.php";

class HeadingStyleHandler implements CSSHandler {
    private ?CSSHandler $nextHandler = null;

    public function setNext(CSSHandler $handler): CSSHandler {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(SimpleXMLElement $XML, array &$css): void {
        foreach($XML->xpath('//style:style[@style:family="paragraph"]') as $style) {
            $name = (string) $style["style:name"];
            $fontSize = (string) ($style->xpath("style:text-properties/@fo:font-size")[0] ?? "inherit");
            $lineHeight = (string) ($style->xpath("style:paragraph-properties/@fo:line-height")[0] ?? "normal");

            $css[] = ".$name { font-size: $fontSize; line-height: $lineHeight; margin: 0pt 0pt 10pt; }";
        }
        $this->nextHandler?->handle($XML, $css);
    }
}
