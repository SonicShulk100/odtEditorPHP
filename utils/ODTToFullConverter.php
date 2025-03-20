<?php

//HTML Handlers
require_once "utils/handlers/html/DocumentStructureHTMLHandler.php";
require_once "utils/handlers/html/MetadataHTMLHandler.php";
require_once "utils/handlers/html/StyleHTMLHandler.php";
require_once "utils/handlers/html/HeadingHTMLHandler.php";
require_once "utils/handlers/html/ListHTMLHandler.php";
require_once "utils/handlers/html/ParagraphHTMLHandler.php";
require_once "utils/handlers/html/TableHTMLHandler.php";
require_once "utils/handlers/html/ImageHTMLHandler.php";
require_once "utils/handlers/html/TextStyleHTMLHandler.php";
require_once "utils/handlers/html/FrameHTMLHandler.php";
require_once "utils/handlers/html/LinkHTMLHandler.php";
require_once "utils/handlers/html/MathHTMLHandler.php";
require_once "utils/handlers/html/DrawingHTMLHandler.php";

//CSS Handlers
require_once "utils/handlers/css/FontCSSHandler.php";
require_once "utils/handlers/css/ParagraphCSSHandler.php";
require_once "utils/handlers/css/TableCSSHandler.php";
require_once "utils/handlers/css/HeadingCSSHandler.php";
require_once "utils/handlers/css/ListCSSHandler.php";
require_once "utils/handlers/css/ImageCSSHandler.php";
require_once "utils/handlers/css/FrameCSSHandler.php";
require_once "utils/handlers/css/LinkCSSHandler.php";
require_once "utils/handlers/css/PageCSSHandler.php";
require_once "utils/handlers/css/MathCSSHandler.php";
require_once "utils/handlers/css/DrawingCSSHandler.php";

class ODTToFullConverter
{
    //Handlers for both HTML and CSS
    private DocumentStructureHTMLHandler $htmlHandler;
    private PageCSSHandler $cssHandler;

    //Conversion states.
    private array $conversionWarnings = [];
    private array $conversionErrors = [];
    private array $conversionStats = [];

    /**
     * Constructor - Sets up handler chains
     */
    public function __construct()
    {
        // Configuration of HTML handlers chain
        $this->htmlHandler = new DocumentStructureHTMLHandler();
        $this->htmlHandler
            ->setNext(new MetadataHTMLHandler())
            ->setNext(new StyleHTMLHandler())
            ->setNext(new HeadingHTMLHandler())
            ->setNext(new ListHTMLHandler())
            ->setNext(new ParagraphHTMLHandler())
            ->setNext(new TableHTMLHandler())
            ->setNext(new ImageHTMLHandler())
            ->setNext(new TextStyleHTMLHandler())
            ->setNext(new FrameHTMLHandler())
            ->setNext(new LinkHTMLHandler())
            ->setNext(new MathHTMLHandler())
            ->setNext(new DrawingHTMLHandler());

        // Configuration of CSS handlers chain
        $this->cssHandler = new PageCSSHandler();
        $this->cssHandler
            ->setNext(new ParagraphCSSHandler())
            ->setNext(new TableCSSHandler())
            ->setNext(new HeadingCSSHandler())
            ->setNext(new ListCSSHandler())
            ->setNext(new ImageCSSHandler())
            ->setNext(new FrameCSSHandler())
            ->setNext(new LinkCSSHandler())
            ->setNext(new FontCSSHandler())
            ->setNext(new MathCSSHandler())
            ->setNext(new DrawingCSSHandler());
    }

    /**
     * Converts an ODT file to HTML
     *
     * @param string $odtFilePath Path to the ODT file
     * @return string Conversion result containing HTML, warnings, errors and stats
     * @throws RuntimeException|Exception
     */
    public function convert(string $odtFilePath): string
    {
        // Reset conversion states

        $zip = new ZipArchive();

        if(!$zip->open($odtFilePath)){
            throw new RuntimeException("Failed to open ODT file.");
        }

        $content = $zip->getFromName("content.xml");
        $styles = $zip->getFromName("styles.xml");

        if (!$content || !$styles) {
            throw new RuntimeException("ODT file does not contain content.xml or styles.xml.");
        }

        $images = [];
        $css = [];

        $XML = new SimpleXMLElement($styles);

        $htmlContent = $this->htmlHandler->handle($content, $zip, $images);
        $cssContent = $this->cssHandler->handle($XML, $css);

        return ""; //TODO: Implement conversion
    }
}