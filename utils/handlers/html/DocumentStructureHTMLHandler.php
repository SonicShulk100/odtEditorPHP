<?php

require_once "utils/handlers/HTMLHandler.php";;

class DocumentStructureHTMLHandler implements HTMLHandler{
    private ?HTMLHandler $nextHandler = null;


    /**
     * @inheritDoc
     */
    #[Override] public function setNext(HTMLHandler $handler): HTMLHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    /**
     * @inheritDoc
     */
    #[Override] public function handle(string $request, ZipArchive $zip, array $images): string
    {

        $dom = new DOMDocument();

        $dom->loadXML($request);

        $xpath = new DOMXPath($dom);

        $xpath->registerNamespace("office", "urn:oasis:names:tc:opendocument:xmlns:office:1.0");
        $xpath->registerNamespace("text", "urn:oasis:names:tc:opendocument:xmlns:text:1.0");

        $bodyContent = $xpath->query("//office:body/office:text")->item(0);

        if($bodyContent){
            $html = "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'><title>Converted Document</title></head><body>";
            $html .= $this->getInnerXML($bodyContent);
            $html .= "</body></html>";

            $request = $html;
        }

        // Continue with the chain
        return $this->nextHandler?->handle($request, $zip, $images);
    }


    private function getInnerXML(DOMNode $node): false|string
    {
        $doc = $node->ownerDocument;

        $fragment = $doc->createDocumentFragment();

        foreach($node->childNodes as $child){
            $fragment->appendChild($child->cloneNode(true));
        }
        return $doc->saveXML($fragment);
    }
}