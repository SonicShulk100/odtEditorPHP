<?php

require_once "utils/handlers/HTMLHandler.php";

class TextStyleHTMLHandler implements HTMLHandler
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
        $pattern = '/<text:span text:style-name="([^"]*)">(.*?)<\/text:span>/s';
        $replacement = '<span class="t-$1">$2</span>';
        $request = preg_replace($pattern, $replacement, $request);

        // Handle bold text
        $request = preg_replace(
            '/<span class="t-(T[23])">(.*?)<\/span>/s',
            '<strong>$2</strong>',
            $request
        );

        // Handle italic text
        $request = preg_replace(
            '/<span class="t-(T1)">(.*?)<\/span>/s',
            '<em>$2</em>',
            $request
        );

        return $this->nextHandler?->handle($request, $zip, $images);
    }
}