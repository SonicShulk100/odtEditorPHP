<?php

require_once "utils/handlers/CSSHandler.php";

class TableCSSHandler implements CSSHandler
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
        $existingTables = [];
        foreach($XML->xpath("//table:table") as $table){
            $name = (string) $table["table:name"];
            $tableRule = ".$name { border-collapse: collapse; }";

            if(!in_array($tableRule, $existingTables, true)){
                $existingTables[] = $tableRule;
                $css[] = $tableRule;
            }
        }

        $this->nextHandler?->handle($XML, $css);
    }
}