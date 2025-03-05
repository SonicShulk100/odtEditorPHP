<?php

require_once "utils/CSSHandler.php";

class ListCSSHandler implements CSSHandler {
    private ?CSSHandler $nextHandler = null;

    public function setNext(CSSHandler $handler): CSSHandler {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(SimpleXMLElement $XML, array &$css): void {
        $existingLists = [];

        // Handle bullet list styles
        foreach ($XML->xpath("//text:list-style/text:list-level-style-bullet") as $list) {
            $level = (int) $list["text:level"];
            if ($level < 1) {
                continue;
            } // Ensure nth-child starts at 1

            $bulletChar = (string) ($list["text:bullet-char"] ?? "•");
            $marginLeft = (string) ($list->xpath("style:list-level-properties/@fo:margin-left")[0] ?? "1em");
            $textIndent = (string) ($list->xpath("style:list-level-properties/@fo:text-indent")[0] ?? "0em");

            $listRule = "ul li:nth-child($level) { 
                list-style-type: \"$bulletChar\"; 
                margin-left: $marginLeft; 
                text-indent: $textIndent;
            }";

            if (!in_array($listRule, $existingLists, true)) {
                $css[] = $listRule;
                $existingLists[] = $listRule;
            }
        }

        // Handle numbered list styles
        foreach ($XML->xpath("//text:list-style/text:list-level-style-number") as $list) {
            $level = (int) $list["text:level"];
            if ($level < 1) {
                continue;
            }

            $numFormat = (string) ($list["style:num-format"] ?? "1");
            $marginLeft = (string) ($list->xpath("style:list-level-properties/@fo:margin-left")[0] ?? "1em");
            $textIndent = (string) ($list->xpath("style:list-level-properties/@fo:text-indent")[0] ?? "0em");

            $listRule = "ol li:nth-child($level) { 
                list-style-type: decimal; 
                margin-left: $marginLeft; 
                text-indent: $textIndent;
                num-format: $numFormat;
            }";

            if (!in_array($listRule, $existingLists, true)) {
                $css[] = $listRule;
                $existingLists[] = $listRule;
            }
        }

        // Pass to the next handler
        $this->nextHandler?->handle($XML, $css);
    }
}
