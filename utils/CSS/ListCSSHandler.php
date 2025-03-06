<?php

require_once "utils/CSSHandler.php";

class ListCSSHandler implements CSSHandler {
    private ?CSSHandler $nextHandler = null;

    public function setNext(CSSHandler $handler): CSSHandler {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(SimpleXMLElement $XML, array &$css): void {
        // Handle unordered list styles
        $css[] = ".awlist1 { list-style: none; counter-reset: awlistcounter1; }";
        $css[] = ".awlist1 > li:before { content: '- '; counter-increment: awlistcounter1; }";

        // Handle ordered list styles
        $css[] = ".awlist2 { list-style: none; counter-reset: awlistcounter2; }";
        $css[] = ".awlist2 > li:before { content: counter(awlistcounter2) '. '; counter-increment: awlistcounter2; }";

        // Pass to next handler
        $this->nextHandler?->handle($XML, $css);
    }
}
