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

        // Process list structures

        // Simple pattern for unordered lists
        $request = preg_replace(
            '/<text:list[^>]*>(.*?)<\/text:list>/s',
            '<ul>$1</ul>',
            $request
        );

        // List items
        $request = preg_replace(
            '/<text:list-item>(.*?)<\/text:list-item>/s',
            '<li>$1</li>',
            $request
        );

        // Clean up list paragraphs
        $request = preg_replace(
            '/<li><text:p[^>]*>(.*?)<\/text:p><\/li>/s',
            '<li>$1</li>',
            $request
        );

        return $this->nextHandler?->handle($request, $zip, $images);
    }
}