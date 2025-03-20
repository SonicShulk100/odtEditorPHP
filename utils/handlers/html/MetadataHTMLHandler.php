<?php

require_once "utils/handlers/HTMLHandler.php";

class MetadataHTMLHandler implements HTMLHandler{
    private ?HTMLHandler $nextHandler = null;

    /**
     * @inheritDoc
     */
    #[Override] public function setNext(HTMLHandler $handler): HTMLHandler
    {
        $this->nextHandler = $handler;
        return $this->nextHandler;
    }

    /**
     * @inheritDoc
     */
    #[Override] public function handle(string $request, ZipArchive $zip, array $images): string
    {
        $request = preg_replace("/<office:meta[^>]*>/", "<div>", $request);
        $request = preg_replace("/<\/office:meta>/", "</div>", $request);

        return $this->nextHandler?->handle($request, $zip, $images);
    }
}