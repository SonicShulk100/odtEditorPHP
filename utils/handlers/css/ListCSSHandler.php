<?php

require_once "utils/handlers/CSSHandler.php";

class ListCSSHandler implements CSSHandler
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
    #[Override] public function handle(SimpleXMLElement $XML, array &$css): void
    {
        $existingLists = [];

        foreach($XML->xpath("//style:list-style/list-level-style-bullet") as $list){
            $level = (int) $list["text:level"];

            $bulletChar = (string) ($list->xpath("style:list-level-properties/@style:num-suffix")[0] ?? "•");

            $listRule = "ul li { list-style-type: none; } ul li::before { content: '$bulletChar'; margin-right: 0.5em; }";

            if(!in_array($listRule, $existingLists, true)){
                $existingLists[] = $listRule;
                $css[] = $listRule;
            }
        }

        foreach($XML->xpath("//style:list-style/list-level-style-number") as $list){
            $level = (int) $list["text:level"];

            $numPrefix = (string) ($list->xpath("style:list-level-properties/@style:num-prefix")[0] ?? "");

            $listRule = "ol li { list-style-type: none; } ol li::before { content: '$numPrefix counter(list-item) '.'; margin-right: 0.5em; }";

            if(!in_array($listRule, $existingLists, true)){
                $existingLists[] = $listRule;
                $css[] = $listRule;
            }
        }

        $this->nextHandler?->handle($XML, $css);
    }
}