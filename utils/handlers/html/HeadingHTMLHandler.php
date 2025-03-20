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
            "P5" => "h3",
            "P6" => "h4",
            "P7" => "h5",
            "P8" => "h6",
        ];

        foreach($headingStyle as $style => $heading){
            $request = preg_replace("/<text:p text:style-name=\"$style\">/", "<$heading>", $request);
            $request = preg_replace("/<\/text:p>/", "</$heading>", $request);
        }

        return $this->nextHandler?->handle($request, $zip, $images);
    }
}