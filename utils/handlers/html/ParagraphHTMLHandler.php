<?php

require_once "utils/handlers/HTMLHandler.php";

class ParagraphHTMLHandler implements HTMLHandler
{
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
        $pattern = '/<text:p text:style-name="([^"]*)">(.*?)<\/text:p>/s';

        $replacement = '<p class="p-$1">$2</p>';

        $request = preg_replace($pattern, $replacement, $request);

        $request = preg_replace("/<text:p>(.*?)<\/text:p>/s", "<p>$1</p>", $request);

        return $this->nextHandler?->handle($request, $zip, $images);
    }
}