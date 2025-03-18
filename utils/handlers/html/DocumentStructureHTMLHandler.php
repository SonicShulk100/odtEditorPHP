<?php

require_once "utils/HTMLHandler.php";

class DocumentStructureHTMLHandler extends HTMLHandler {
    public function handle($content, ZipArchive $zip, &$images): string
    {
        // Fixes for content loading
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        libxml_use_internal_errors(true);

        // Properly handle XML namespaces
        if (!preg_match('/<\?xml/', $content)) {
            $content = '<?xml version="1.0" encoding="UTF-8"?>' . $content;
        }

        $loadResult = $dom->loadXML($content);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        if (!$loadResult) {
            // Handle XML parsing errors
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = "Line $error->line: $error->message";
            }
            throw new RuntimeException("Failed to parse XML: " . implode("; ", $errorMessages));
        }

        $xpath = new DOMXPath($dom);

        // Register all namespaces from the document
        $this->registerNamespaces($xpath, $dom);

        // Get the body content
        $bodyContent = $xpath->query("//office:body/office:text")->item(0);

        // If there is no body-content, throw an exception
        if (!$bodyContent) {
            throw new RuntimeException("No body content found in the document");
        }

        // Create a complete HTML document structure
        $html = '<!DOCTYPE html>' . "\n";
        $html .= '<html lang="en">' . "\n";
        $html .= '<head>' . "\n";
        $html .= '    <meta charset="UTF-8">' . "\n";
        $html .= '    <meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
        $html .= '    <title>Converted Document</title>' . "\n";
        $html .= '    <style></style>' . "\n";
        $html .= '</head>' . "\n";
        $html .= '<body>' . "\n";

        // Preserve document structure
        $tempDom = new DOMDocument('1.0', 'UTF-8');
        $tempDom->preserveWhiteSpace = false;
        $tempDom->formatOutput = true;

        // Process and import each child node
        foreach ($bodyContent->childNodes as $childNode) {
            $imported = $tempDom->importNode($childNode, true);
            $tempDom->appendChild($imported);
        }

        // Get the processed content
        $bodyHtml = $tempDom->saveXML();

        // Append the body content
        $html .= $bodyHtml . "\n";
        $html .= '</body>' . "\n";
        $html .= '</html>';

        // Continue with the chain
        return parent::handle($html, $zip, $images);
    }

    /**
     * Register all namespaces from the document
     *
     * @param DOMXPath $xpath XPath object
     * @param DOMDocument $dom Document object
     */
    private function registerNamespaces(DOMXPath $xpath, DOMDocument $dom): void
    {
        // Get the root element
        $root = $dom->documentElement;

        if (!$root) {
            return;
        }

        // Get all attributes
        $attributes = $root->attributes;

        // Register all xmlns attributes
        foreach ($attributes as $attr) {
            if (str_starts_with($attr->name, 'xmlns:')) {
                $prefix = substr($attr->name, 6); // Remove 'xmlns:'
                $namespace = $attr->value;
                $xpath->registerNamespace($prefix, $namespace);
            }
        }

        // Add standard namespaces for ODF
        $namespaces = [
            'office' => 'urn:oasis:names:tc:opendocument:xmlns:office:1.0',
            'text' => 'urn:oasis:names:tc:opendocument:xmlns:text:1.0',
            'style' => 'urn:oasis:names:tc:opendocument:xmlns:style:1.0',
            'draw' => 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0',
            'table' => 'urn:oasis:names:tc:opendocument:xmlns:table:1.0',
            'xlink' => 'http://www.w3.org/1999/xlink',
            'fo' => 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0',
            'svg' => 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0',
            'dc' => 'https://purl.org/dc/elements/1.1/',
            'meta' => 'urn:oasis:names:tc:opendocument:xmlns:meta:1.0',
            'number' => 'urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0',
            'presentation' => 'urn:oasis:names:tc:opendocument:xmlns:presentation:1.0',
            'dr3d' => 'urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0',
            'math' => 'http://www.w3.org/1998/Math/MathML',
            'form' => 'urn:oasis:names:tc:opendocument:xmlns:form:1.0',
            'script' => 'urn:oasis:names:tc:opendocument:xmlns:script:1.0',
            'ooo' => 'https://openoffice.org/2004/office',
            'ooow' => 'https://openoffice.org/2004/writer',
            'oooc' => 'https://openoffice.org/2004/calc',
            'dom' => 'http://www.w3.org/2001/xml-events',
            'xforms' => 'http://www.w3.org/2002/xforms',
            'xsd' => 'http://www.w3.org/2001/XMLSchema',
            'xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            'rpt' => 'https://openoffice.org/2005/report',
            'of' => 'urn:oasis:names:tc:opendocument:xmlns:of:1.2',
            'xhtml' => 'http://www.w3.org/1999/xhtml',
            'grddl' => 'http://www.w3.org/2003/g/data-view#',
            'tableooo' => 'https://openoffice.org/2009/table',
            'drawooo' => 'https://openoffice.org/2010/draw',
            'calcext' => 'urn:org:documentfoundation:names:experimental:calc:xmlns:calcext:1.0'
        ];

        foreach ($namespaces as $prefix => $uri) {
            try {
                $xpath->registerNamespace($prefix, $uri);
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
    }
}