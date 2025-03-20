<?php

require_once "utils/handlers/HTMLHandler.php";

class ListHTMLHandler implements HTMLHandler{
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

        $request = preg_replace("/<text:list[^>]*>/", "<ul>", $request);

        $request = preg_replace("/<text:list-item>(.*?)<\/text:list-item>/", "<li>$1</li>", $request);

        $request = preg_replace("/<li><text:p>(.*?)<\/text:p><\/li>/", "<li>$1</li>", $request);

        return $this->nextHandler?->handle($request, $zip, $images);
    }
}