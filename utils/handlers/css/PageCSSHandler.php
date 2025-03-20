<?php

require_once "utils/handlers/CSSHandler.php";

class PageCSSHandler implements CSSHandler
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
        $existingPages = [];
        foreach($XML->xpath("//style:master-page") as $page){
            $name = (string) $page["style:name"];
            $pageRule = ".$name { display: block; }";

            if(!in_array($pageRule, $existingPages, true)){
                $existingPages[] = $pageRule;
                $css[] = $pageRule;
            }
        }

        return $this->nextHandler?->handle($XML, $css);
    }
}