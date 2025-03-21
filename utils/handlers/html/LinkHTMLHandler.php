<?php

require_once "utils/handlers/HTMLHandler.php";

class LinkHTMLHandler implements HTMLHandler{

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
        $pattern = '/<text:a xlink:gref="([^"]*)">(.*?)<\/text:a>/s';

        $replacement = '<a href="$1">$2</a>';


        return preg_replace($pattern, $replacement, $request);
    }
}