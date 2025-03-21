<?php

require_once "utils/handlers/HTMLHandler.php";

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
        $headingStyle = [
            "P3" => "h1",
            "P4" => "h2",
        ];

        foreach($headingStyle as $style => $heading){
            $pattern = '/<text:p text:style-name="' . $style . '">(.*?)<\/text:p>/s';
            $replacement = "<$heading>$1</$heading>";
            $request = preg_replace($pattern, $replacement, $request);
        }

        return $this->nextHandler?->handle($request, $zip, $images);
    }
}