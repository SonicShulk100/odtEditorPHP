<?php

interface XMLToHTMLHandler {

    /**
     * Set the next handler in the chain
     * @param XMLToHTMLHandler $handler the next handler
     * @return XMLToHTMLHandler the next handler
     */
    public function setNext(XMLToHTMLHandler $handler): XMLToHTMLHandler;

    /**
     * Handle the XML content
     * @param string $xml the XML content
     * @return string the HTML content
     */
    public function handle(string $xml): string;
}
