<?php

require_once "utils/Handler.php";
require_once "utils/handlers/ParagraphHandler.php";
require_once "utils/handlers/HeadingHandler.php";
require_once "utils/handlers/ListHandler.php";
require_once "utils/handlers/ListItemHandler.php";
require_once "utils/handlers/SpanHandler.php";
require_once "utils/handlers/TableHandler.php";
require_once "utils/handlers/TableRowHandler.php";
require_once "utils/handlers/TableCellHandler.php";
require_once "utils/handlers/DefaultHandler.php";

class ODTToFullConverter {
    private Handler $chain;
    private array $styleMapping;

    public function __construct()
    {
        // Initialize the chain of responsibility
        $this->initializeChain();

        // Initialize style mapping
        $this->styleMapping = [
            // Text styles
            'text:p' => 'p',
            'text:h' => 'h',
            'text:list' => 'ul',
            'text:list-item' => 'li',
            'text:span' => 'span',

            // Table styles
            'table:table' => 'table',
            'table:table-row' => 'tr',
            'table:table-cell' => 'td',

            // Style properties mapping
            'fo:font-weight' => 'font-weight',
            'fo:font-style' => 'font-style',
            'fo:font-size' => 'font-size',
            'fo:color' => 'color',
            'fo:background-color' => 'background-color',
            'fo:text-align' => 'text-align',
            'fo:margin-left' => 'margin-left',
            'fo:margin-right' => 'margin-right',
            'fo:margin-top' => 'margin-top',
            'fo:margin-bottom' => 'margin-bottom',
            'fo:padding' => 'padding',
            'fo:line-height' => 'line-height',
            'style:text-underline-style' => 'text-decoration'
        ];
    }

    private function initializeChain(): void
    {
        // Create handlers
        $paragraphHandler = new ParagraphHandler();
        $headingHandler = new HeadingHandler();
        $listHandler = new ListHandler();
        $listItemHandler = new ListItemHandler();
        $spanHandler = new SpanHandler();
        $tableHandler = new TableHandler();
        $tableRowHandler = new TableRowHandler();
        $tableCellHandler = new TableCellHandler();
        $defaultHandler = new DefaultHandler();

        // Set up the chain
        $paragraphHandler->setNext($headingHandler);
        $headingHandler->setNext($listHandler);
        $listHandler->setNext($listItemHandler);
        $listItemHandler->setNext($spanHandler);
        $spanHandler->setNext($tableHandler);
        $tableHandler->setNext($tableRowHandler);
        $tableRowHandler->setNext($tableCellHandler);
        $tableCellHandler->setNext($defaultHandler);

        // Set the first handler as the start of the chain
        $this->chain = $paragraphHandler;
    }

    public function convert(string $contentXml, string $stylesXml): string
    {
        // Load XML content
        $contentDoc = new DOMDocument();
        $contentDoc->loadXML($contentXml);

        $stylesDoc = new DOMDocument();
        $stylesDoc->loadXML($stylesXml);

        // Extract styles from stylesXml
        $styles = $this->extractStyles($stylesDoc);

        // Process content with the chain of handlers
        $html = '';
        $rootElement = $contentDoc->documentElement;

        // Process each child node
        foreach ($rootElement->childNodes as $node) {
            if ($node->nodeType === XML_ELEMENT_NODE) {
                $html .= $this->chain->handle([
                    'node' => $node,
                    'styles' => $styles,
                    'mapping' => $this->styleMapping
                ]);
            }
        }

        return $this->wrapInHtml($html, $styles);
    }

    private function extractStyles(DOMDocument $stylesDoc): array
    {
        $styles = [];
        // Extract style definitions from the styles.xml
        $styleElements = $stylesDoc->getElementsByTagName('style');

        foreach ($styleElements as $style) {
            $styleName = $style->getAttribute('style:name');
            $properties = [];

            $propertyNodes = $style->getElementsByTagName('style:text-properties');
            foreach ($propertyNodes as $propNode) {
                foreach ($this->styleMapping as $odtProp => $cssProp) {
                    if ($propNode->hasAttribute($odtProp)) {
                        $properties[$cssProp] = $propNode->getAttribute($odtProp);
                    }
                }
            }

            $styles[$styleName] = $properties;
        }

        return $styles;
    }

    private function wrapInHtml(string $content, array $styles): string
    {
        $css = $this->generateCSS($styles);

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Converted Document</title>
    <style>
    $css
    </style>
</head>
<body>
    $content
</body>
</html>
HTML;
    }

    private function generateCSS(array $styles): string
    {
        $css = '';

        foreach ($styles as $styleName => $properties) {
            $css .= ".$styleName {\n";

            foreach ($properties as $property => $value) {
                $css .= "    $property: $value;\n";
            }

            $css .= "}\n\n";
        }

        return $css;
    }
}
