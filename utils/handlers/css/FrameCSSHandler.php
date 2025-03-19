 <?php

 require_once "../CSSHandler.php";

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
         return $this->nextHandler?->handle($XML, $css);
     }
 }