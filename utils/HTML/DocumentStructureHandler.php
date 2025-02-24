<?php

require_once "utils/Handler.php";

class DocumentStructureHandler extends Handler {
    public function handle($content, ZipArchive $zip, &$images) {
        // Process the overall document structure
        // Extract the body content from office:document-content

        $dom = new DOMDocument();
        $dom->loadXML($content);
        $xpath = new DOMXPath($dom);

        // Register namespaces
        $xpath->registerNamespace('office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
        $xpath->registerNamespace('text', 'urn:oasis:names:tc:opendocument:xmlns:text:1.0');

        // Get the document body content
        $bodyContent = $xpath->query('//office:body/office:text')->item(0);

        if ($bodyContent) {
            // Create a basic HTML structure
            $html = '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Converted Document</title><style></style></head><body>';
            $html .= $this->getInnerXML($bodyContent);
            $html .= '</body></html>';

            $content = $html;
        }

        // Pass to next handler
        return parent::handle($content, $zip, $images);
    }

    private function getInnerXML(DOMNode $node): false|string
    {
        $doc = $node->ownerDocument;
        $fragment = $doc->createDocumentFragment();
        foreach ($node->childNodes as $child) {
            $fragment->appendChild($child->cloneNode(true));
        }
        return $doc->saveXML($fragment);
    }
}
