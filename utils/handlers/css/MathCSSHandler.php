<?php

require_once "utils/handlers/CSSHandler.php";

class MathCSSHandler implements CSSHandler
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
        $existingMaths = [];
        foreach($XML->xpath("//math:math") as $math){
            $name = (string) $math["draw:name"];
            $mathRule = ".$name { display: inline-block; }";

            if(!in_array($mathRule, $existingMaths, true)){
                $existingMaths[] = $mathRule;
                $css[] = $mathRule;
            }
        }

        return $this->nextHandler?->handle($XML, $css);
    }
}