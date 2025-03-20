<?php

require_once "utils/handlers/CSSHandler.php";

class DrawingCSSHandler implements CSSHandler
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
        $existingDrawings = [];
        foreach($XML->xpath("//draw:frame") as $frame){
            $name = (string) $frame["draw:name"];
            $drawingRule = ".$name { display: inline-block; }";

            if(!in_array($drawingRule, $existingDrawings, true)){
                $existingDrawings[] = $drawingRule;
                $css[] = $drawingRule;
            }
        }

        return preg_replace("/<draw:frame[^>]*>/", "<div>", $XML->asXML());
    }
}