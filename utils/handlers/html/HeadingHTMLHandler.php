<?php

require "../HTMLHandler.php";

class HeadingHTMLHandler implements HTMLHandler{

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
        return $this->nextHandler?->handle($request, $zip, $images);
    }
}