<?php

//Importations des classes nécessaires.
require_once 'Handler.php';
require_once 'HTML/ImageHandler.php';
require_once 'HTML/ParagraphHandler.php';
require_once 'HTML/StyleHandler.php';
require_once 'HTML/ListHandler.php';
require_once 'HTML/TableHandler.php';
require_once 'HTML/FontHandler.php';
require_once 'HTML/MarginHandler.php';
require_once 'HTML/HeaderFooterHandler.php';
require_once 'HTML/RootHandler.php';
require_once 'HTML/SequenceDeclsHandler.php';
require_once 'HTML/StyleAttributeHandler.php';
require_once 'HTML/FrameHandler.php';
require_once 'HTML/AutomaticStylesHandler.php';
require_once 'HTML/BodyHandler.php';
require_once 'HTML/TextHandler.php';
require_once 'HTML/TabHandler.php';
require_once 'HTML/SpanHandler.php';
require_once 'HTML/ListItemHandler.php';
require_once 'HTML/FontFaceDeclsHandler.php';
require_once 'HTML/TabStopHandler.php';

/**
 * Convertisseur de fichier ODT en HTML
 */
class ODTToHTMLConverter {
    private $handler;

    /**
     * Constructeur de la classe
     */
    public function __construct() {
        $this->handler = new ImageHandler();
        $this->handler
            ->setNext(new ParagraphHandler())
            ->setNext(new StyleHandler())
            ->setNext(new ListHandler())
            ->setNext(new TableHandler())
            ->setNext(new FontHandler())
            ->setNext(new MarginHandler())
            ->setNext(new HeaderFooterHandler())
            ->setNext(new RootHandler())
            ->setNext(new SequenceDeclsHandler())
            ->setNext(new StyleAttributeHandler())
            ->setNext(new FrameHandler())
            ->setNext(new AutomaticStylesHandler())
            ->setNext(new BodyHandler())
            ->setNext(new TextHandler())
            ->setNext(new TabHandler())
            ->setNext(new SpanHandler())
            ->setNext(new ListItemHandler())
            ->setNext(new FontFaceDeclsHandler())
            ->setNext(new TabStopHandler());
    }

    /**
     * Convertit un fichier ODT en HTML
     * @param string $odtFilePath le chemin du fichier ODT
     * @return array|string|string[]|null le contenu HTML
     * @throws RuntimeException si le fichier ODT ne peut pas être ouvert ou si le fichier content.xml ne peut pas être extrait
     */
    public function convert(string $odtFilePath) : array|string|null{
        $zip = new ZipArchive();
        if ($zip->open($odtFilePath) === TRUE) {
            $content = $zip->getFromName('content.xml');

            if ($content !== false) {
                return $this->handler->handle($content, $zip, $images);
            }

            throw new RuntimeException('Could not extract content.xml from ODT file.');
        }

        $zip->close();

        throw new RuntimeException('Could not open ODT file.');
    }
}
