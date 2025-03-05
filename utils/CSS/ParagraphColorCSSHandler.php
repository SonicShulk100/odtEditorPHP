<?php

require_once "utils/CSSHandler.php";

/**
 * @inheritDoc
 */
class ParagraphColorCSSHandler implements CSSHandler
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
        foreach ($XML->xpath("//style:style[@style:family='paragraph'] | //style:default-style[@style:family='paragraph']") as $style) {
            $color = (string) ($style->xpath("style:text-properties/@fo:color")[0] ?? null);
            if ($color) {
                $styleName = (string) $style["style:name"] ?: "default-paragraph";
                $css[] = ".$styleName { color: $color; }";
            }
        }

        $this->nextHandler?->handle($XML, $css);
    }
}
