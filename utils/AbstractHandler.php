<?php

require_once "utils/XMLToHTMLHandler.php";

abstract class AbstractHandler implements XMLToHTMLHandler {
    protected ?XMLToHTMLHandler $nextHandler = null;

    public function setNext(XMLToHTMLHandler $handler): XMLToHTMLHandler {
        $this->nextHandler = $handler;
        return $handler;
    }

    /**
     * Handle the XML content
     * @param string $xml the XML content
     * @return string the HTML content
     */
    public function handle(string $xml): string {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($xml);
        }
        return $xml;
    }
}
