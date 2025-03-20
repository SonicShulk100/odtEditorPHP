<?php

require_once "utils/handlers/CSSHandler.php";

class ParagraphCSSHandler implements CSSHandler
{
    private ?CSSHandler $nextHandler = null;

    /**
     * @inheritDoc
     */
    #[Override] public function setNext(CSSHandler $handler): CSSHandler
    {
        $this->nextHandler = $handler;
        return $this->nextHandler;
    }

    /**
     * @inheritDoc
     */
    #[Override] public function handle(SimpleXMLElement $XML, array &$css): string
    {
        $existingParagraphs = [];
        foreach($XML->xpath("//style:style[@style:family='paragraph']") as $style){
            $name = (string) $style["style:name"];
            $textAlign = (string) ($style->xpath("style:paragraph-properties/@fo:text-align")[0] ?? "inherit");
            $lineHeight = (string) ($style->xpath("style:paragraph-properties/@style:line-height")[0] ?? "inherit");
            $margin = (string) ($style->xpath("style:paragraph-properties/@fo:margin-top")[0] ?? "inherit");
            $padding = (string) ($style->xpath("style:paragraph-properties/@fo:padding-top")[0] ?? "inherit");
            $paragraphRule = ".$name { text-align: $textAlign; line-height: $lineHeight; margin-top: $margin; padding-top: $padding; }";

            if(!in_array($paragraphRule, $existingParagraphs, true)){
                $existingParagraphs[] = $paragraphRule;
                $css[] = $paragraphRule;
            }
        }

        return $this->nextHandler?->handle($XML, $css);
    }
}