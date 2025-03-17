<?php

require_once "utils/HTMLHandler.php";

class StyleHTMLHandler extends HTMLHandler
{
    public function handle($content, ZipArchive $zip, &$images): string
    {
        // Extract style information from the ODT file directly
        $stylesXml = $zip->getFromName('styles.xml');
        $contentXml = $zip->getFromName('content.xml');

        $css = $this->extractStyles($stylesXml, $contentXml);

        // Insert CSS into the HTML head
        if (!empty($css)) {
            $content = preg_replace('/<style><\/style>/', "<style>\n$css</style>", $content);
        }

        // Pass to next handler
        return parent::handle($content, $zip, $images);
    }

    private function extractStyles($stylesXml, $contentXml): string
    {
        $css = "";

        // Process both style sources
        foreach ([$stylesXml, $contentXml] as $xmlSource) {
            if (empty($xmlSource)) {
                continue;
            }

            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $result = $dom->loadXML($xmlSource);
            libxml_clear_errors();

            if (!$result) {
                continue;
            }

            $xpath = new DOMXPath($dom);

            // Register all necessary namespaces
            $xpath->registerNamespace('office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
            $xpath->registerNamespace('style', 'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
            $xpath->registerNamespace('fo', 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0');

            // Get both automatic and named styles
            $styleQueries = [
                '//office:automatic-styles/style:style',
                '//office:styles/style:style'
            ];

            foreach ($styleQueries as $query) {
                $styleNodes = $xpath->query($query);

                if ($styleNodes && $styleNodes->length > 0) {
                    foreach ($styleNodes as $styleNode) {
                        $css .= $this->processStyleNode($styleNode, $xpath);
                    }
                }
            }
        }

        return $css;
    }

    private function processStyleNode($styleNode, $xpath): string
    {
        $css = "";
        $styleName = $styleNode->getAttribute("style:name");
        $styleFamily = $styleNode->getAttribute("style:family");

        if (empty($styleName) || empty($styleFamily)) {
            return "";
        }

        if ($styleFamily === 'paragraph') {
            $css .= ".p-$styleName {\n";
            $css .= $this->extractParagraphProperties($styleNode, $xpath);
            $css .= $this->extractTextProperties($styleNode, $xpath);
            $css .= "}\n";
        } elseif ($styleFamily === 'text') {
            $css .= ".t-$styleName {\n";
            $css .= $this->extractTextProperties($styleNode, $xpath);
            $css .= "}\n";
        } elseif ($styleFamily === 'table') {
            $css .= "table.$styleName {\n";
            $css .= $this->extractTableProperties($styleNode, $xpath);
            $css .= "}\n";
        }

        return $css;
    }

    private function extractParagraphProperties($styleNode, $xpath): string
    {
        $css = "";
        $paragraphProps = $xpath->query('.//style:paragraph-properties', $styleNode);

        if ($paragraphProps->length > 0) {
            $node = $paragraphProps->item(0);

            // Map common paragraph properties
            $mappings = [
                'fo:margin-left' => 'margin-left',
                'fo:margin-right' => 'margin-right',
                'fo:margin-top' => 'margin-top',
                'fo:margin-bottom' => 'margin-bottom',
                'fo:text-indent' => 'text-indent',
                'fo:text-align' => 'text-align',
                'fo:line-height' => 'line-height'
            ];

            foreach ($mappings as $odtAttr => $cssAttr) {
                $value = $node->getAttribute($odtAttr);
                if ($value) {
                    $css .= "  $cssAttr: $value;\n";
                }
            }
        }

        return $css;
    }

    private function extractTextProperties($styleNode, $xpath): string
    {
        $css = "";
        $textProps = $xpath->query('.//style:text-properties', $styleNode);

        if ($textProps->length > 0) {
            $node = $textProps->item(0);

            // Map common text properties
            $mappings = [
                'fo:font-size' => 'font-size',
                'fo:color' => 'color',
                'fo:background-color' => 'background-color'
            ];

            foreach ($mappings as $odtAttr => $cssAttr) {
                $value = $node->getAttribute($odtAttr);
                if ($value) {
                    $css .= "  $cssAttr: $value;\n";
                }
            }

            // Handle specific text properties
            if ($node->getAttribute('fo:font-weight') === 'bold') {
                $css .= "  font-weight: bold;\n";
            }

            if ($node->getAttribute('fo:font-style') === 'italic') {
                $css .= "  font-style: italic;\n";
            }

            if ($node->getAttribute('style:text-underline-style') === 'solid') {
                $css .= "  text-decoration: underline;\n";
            }
        }

        return $css;
    }

    private function extractTableProperties($styleNode, $xpath): string
    {
        $css = "";
        $tableProps = $xpath->query('.//style:table-properties', $styleNode);

        if ($tableProps->length > 0) {
            $node = $tableProps->item(0);

            // Map common table properties
            $mappings = [
                'style:width' => 'width',
                'fo:margin-left' => 'margin-left',
                'fo:margin-right' => 'margin-right',
                'fo:margin-top' => 'margin-top',
                'fo:margin-bottom' => 'margin-bottom'
            ];

            foreach ($mappings as $odtAttr => $cssAttr) {
                $value = $node->getAttribute($odtAttr);
                if ($value) {
                    $css .= "  $cssAttr: $value;\n";
                }
            }

            // Handle borders
            if ($node->getAttribute('table:border-model') === 'collapsing') {
                $css .= "  border-collapse: collapse;\n";
            }
        }

        return $css;
    }
}