<?php

require_once "utils/handlers/HTMLHandler.php";
class MathHTMLHandler implements HTMLHandler{
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
        $request = preg_replace("/<draw:object[^>]*>/", "<div>", $request);
        $request = preg_replace("/<\/draw:object>/", "</div>", $request);

        return $this->nextHandler?->handle($request, $zip, $images);
    }
}