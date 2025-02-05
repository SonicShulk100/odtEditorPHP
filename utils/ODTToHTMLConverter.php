<?php

require_once "utils/HTML/ParagraphHandler.php";
require_once "utils/HTML/ListHandler.php";
require_once "utils/HTML/TableHandler.php";
require_once "utils/HTML/TextFormattingHandler.php";
require_once "utils/HTML/PageMarginsHandler.php";
require_once "utils/HTML/HyperlinkHandler.php";
require_once "utils/HTML/FontHandler.php";
require_once "utils/HTML/HeaderFooterHandler.php";

class ODTToHTMLConverter
{
    private XMLReader $reader;
    private array $handlers;

    public function __construct(){
        $this->reader = new XMLReader();
        $this->handlers = [];

        // Add handlers
        $paragraphHandler = new ParagraphHandler();
        $listHandler = new ListHandler();
        $tableHandler = new TableHandler();
        $textFormattingHandler = new TextFormattingHandler();
        $pageMarginsHandler = new PageMarginsHandler();
        $hyperlinkHandler = new HyperlinkHandler();
        $fontHandler = new FontHandler();
        $headerFooterHandler = new HeaderFooterHandler();

        $paragraphHandler->setNext($listHandler)
            ->setNext($tableHandler)
            ->setNext($textFormattingHandler)
            ->setNext($pageMarginsHandler)
            ->setNext($hyperlinkHandler)
            ->setNext($fontHandler)
            ->setNext($headerFooterHandler);

        $this->handlers[] = $paragraphHandler;

    }

    public function convert(string $odtPath): string{
        $this->reader::open("zip://$odtPath#content.xml");

        $htmlContent = [];

        while($this->reader->read()){
            foreach($this->handlers as $handler){
                $result = $handler->handle($this->reader);
                if($result !== null){
                    $htmlContent[] = $result;
                }
            }
        }

        return "<html lang='fr'><head><meta charset='UTF-8'><title>HTML CONVERTED</title></head><body>" .
            implode("\n", $htmlContent) .
            "</body></html>";
    }
}
