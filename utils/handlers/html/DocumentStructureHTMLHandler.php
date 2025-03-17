<?php

require_once "utils/HTMLHandler.php";

class DocumentStructureHTMLHandler extends HTMLHandler {
    public function handle($content, ZipArchive $zip, &$images): string
    {
        $dom = new DOMDocument();

        libxml_use_internal_errors(true);

        $dom->loadHTML($content);

        $xpath = new DOMXPath($dom);

        //Registering namespaces
        $xpath->registerNamespace("office", "urn:oasis:names:tc:opendocument:xmlns:office:1.0");
        $xpath->registerNamespace("text", "urn:oasis:names:tc:opendocument:xmlns:text:1.0");
        $xpath->registerNamespace("style", "urn:oasis:names:tc:opendocument:xmlns:style:1.0");
        $xpath->registerNamespace("draw", "urn:oasis:names:tc:opendocument:xmlns:drawing:1.0");
        $xpath->registerNamespace("table", "urn:oasis:names:tc:opendocument:xmlns:table:1.0");
        $xpath->registerNamespace("xlink", "http://www.w3.org/1999/xlink");
        $xpath->registerNamespace("fo", "urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0");
        $xpath->registerNamespace("svg", "urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0");
        $xpath->registerNamespace("dc", "https://purl.org/dc/elements/1.1/");
        $xpath->registerNamespace("meta", "urn:oasis:names:tc:opendocument:xmlns:meta:1.0");
        $xpath->registerNamespace("number", "urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0");
        $xpath->registerNamespace("presentation", "urn:oasis:names:tc:opendocument:xmlns:presentation:1.0");
        $xpath->registerNamespace("dr3d", "urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0");
        $xpath->registerNamespace("math", "http://www.w3.org/1998/Math/MathML");
        $xpath->registerNamespace("form", "urn:oasis:names:tc:opendocument:xmlns:form:1.0");
        $xpath->registerNamespace("script", "urn:oasis:names:tc:opendocument:xmlns:script:1.0");
        $xpath->registerNamespace("ooo", "http://openoffice.org/2004/office");
        $xpath->registerNamespace("ooow", "http://openoffice.org/2004/writer");
        $xpath->registerNamespace("oooc", "http://openoffice.org/2004/calc");
        $xpath->registerNamespace("dom", "http://www.w3.org/2001/xml-events");
        $xpath->registerNamespace("xforms", "http://www.w3.org/2002/xforms");
        $xpath->registerNamespace("xsd", "http://www.w3.org/2001/XMLSchema");
        $xpath->registerNamespace("xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $xpath->registerNamespace("rpt", "http://openoffice.org/2005/report");
        $xpath->registerNamespace("of", "urn:oasis:names:tc:opendocument:xmlns:of:1.2");
        $xpath->registerNamespace("xhtml", "http://www.w3.org/1999/xhtml");
        $xpath->registerNamespace("grddl", "http://www.w3.org/2003/g/data-view#");
        $xpath->registerNamespace("tableooo", "http://openoffice.org/2009/table");
        $xpath->registerNamespace("drawooo", "http://openoffice.org/2010/draw");
        $xpath->registerNamespace("calcext", "urn:org:documentfoundation:names:experimental:calc:xmlns:calcext:1.0");

        //Getting the body content
        $bodyContent = $xpath->query("//office:body/office:text")->item(0);

        //If there is no body-content, throw an exception
        if($bodyContent){
            //Creating the HTML document
            $html = '<!DOCTYPE html><html lang="fr"><head"><meta charset="UTF-8">';
            $html .= '<title>Converted Document</title>';
            $html .= '<style></style></head><body>';

            //Importing the body content into the HTML document
            $tempDom = new DOMDocument();
            foreach($bodyContent->childNodes as $childNode){
                $imported = $tempDom->importNode($childNode, true);
                $tempDom->appendChild($imported);
            }

            //Appending the body content to the HTML document
            $html .= $tempDom->saveXML();
            $html .= '</body></html>';

            //Setting the content to the HTML document
            $content = $html;
        }

        //Returning the content
        return parent::handle($content, $zip, $images);
    }
}
