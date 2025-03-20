<?php

require_once "utils/handlers/HTMLHandler.php";

class FrameHTMLHandler implements HTMLHandler{
    private ?HTMLHandler $nextHandler = null;

    /**
     * @inheritDoc
     */
    #[Override] public function setNext(HTMLHandler $handler): HTMLHandler{
        $this->nextHandler = $handler;
        return $handler;
    }

    /**
     * @inheritDoc
     */
    #[Override] public function handle(string $request, ZipArchive $zip, array $images): string
    {
        $request = preg_replace("/<draw:frame[^>]*>/", "<div>", $request);
        $request = preg_replace("/<\/draw:frame>/", "</div>", $request);

        return $this->nextHandler?->handle($request, $zip, $images);
    }
}
