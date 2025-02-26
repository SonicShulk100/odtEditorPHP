<?php

require_once "utils/CSSHandler.php";

/**
 * @inheritDoc
 */
class ParagraphCSSHandler implements CSSHandler{
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
        foreach ($XML->xpath("//style:style[style:family=pargraph]") as $style) {
            $name = (string)$style["style:name"];
            $marginTop = (string)($style->xpath("style:paragraph-properties/@fo:margin-top")[0] ?? "0");
            $marginLeft = (string)($style->xpath("style:paragraph-properties/@fo:margin-left")[0] ?? "0");
            $marginRight = (string)($style->xpath("style:paragraph-properties/@fo:margin-right")[0] ?? "0");
            $marginBottom = (string)($style->xpath("style:paragraph-properties/@fo:margin-bottom")[0] ?? "0");

            $css[] = ".$name { margin-top : $marginTop; margin-left : $marginLeft; margin-right: $marginRight; margin-bottom: $marginBottom }";

        }

        $this->nextHandler?->handle($XML, $css);

        // TODO: Implement handle() method.
    }
}