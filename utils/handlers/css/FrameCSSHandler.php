 <?php

 require_once "utils/handlers/CSSHandler.php";

 class FrameCSSHandler implements CSSHandler
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
         $existingFrames = [];

         foreach($XML->xpath("//draw:frame") as $frame){
             $name = (string) $frame["draw:name"];
             $frameRule = ".$name { display: inline-block; }";

             if(!in_array($frameRule, $existingFrames, true)){
                 $existingFrames[] = $frameRule;
                 $css[] = $frameRule;
             }
         }

         return $this->nextHandler?->handle($XML, $css);
     }
 }