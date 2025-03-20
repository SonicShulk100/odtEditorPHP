<?php

require_once "utils/handlers/HTMLHandler.php";

class DrawingHTMLHandler implements HTMLHandler{
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
        $request = preg_replace("/<draw:frame[^>]*>/", "<div>", $request);
        return preg_replace("/<\/draw:frame>/", "</div>", $request);
    }
}