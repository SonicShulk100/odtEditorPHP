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
        $request = preg_replace("/<table:table[^>]*>/", "<table>", $request);
        $request = preg_replace("/<\/table:table>/", "</table>", $request);

        $request = preg_replace("/<table:table-row[^>]*>/", "<tr>", $request);
        $request = preg_replace("/<\/table:table-row>/", "</tr>", $request);

        $request = preg_replace("/<table:table-cell[^>]*>/", "<td>", $request);
        $request = preg_replace("/<\/table:table-cell>/", "</td>", $request);


        $request = preg_replace("/<text:table[^>]*>/", "<table>", $request);
        $request = preg_replace("/<\/text:table>/", "</table>", $request);

        $request = preg_replace("/<text:table-row[^>]*>/", "<tr>", $request);
        $request = preg_replace("/<\/text:table-row>/", "</tr>", $request);

        $request = preg_replace("/<text:table-cell[^>]*>/", "<td>", $request);
        $request = preg_replace("/<\/text:table-cell>/", "</td>", $request);

        return $this->nextHandler?->handle($request, $zip, $images);
    }
}