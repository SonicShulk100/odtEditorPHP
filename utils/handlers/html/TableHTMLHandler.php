<?php

require_once "utils/handlers/HTMLHandler.php";

class TableHTMLHandler implements HTMLHandler
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
        // Process table structures

        // Convert tables
        $request = preg_replace(
            '/<table:table[^>]*>(.*?)<\/table:table>/s',
            '<table>$1</table>',
            $request
        );

        // Convert rows
        $request = preg_replace(
            '/<table:table-row[^>]*>(.*?)<\/table:table-row>/s',
            '<tr>$1</tr>',
            $request
        );

        // Convert cells
        $request = preg_replace(
            '/<table:table-cell[^>]*>(.*?)<\/table:table-cell>/s',
            '<td>$1</td>',
            $request
        );

        // Clean up cell paragraphs
        $request = preg_replace(
            '/<td><text:p[^>]*>(.*?)<\/text:p><\/td>/s',
            '<td>$1</td>',
            $request
        );

        return $this->nextHandler?->handle($request, $zip, $images);
    }
}