<?php

require_once "utils/HTMLHandler.php";

/**
 *
 */
class StyleHTMLHandler extends HTMLHandler
{
    public function handle($content, ZipArchive $zip, &$images)
    {
        // Extract and process style information
        // Convert ODT styles to CSS

        // Find style definitions in the content
        if (preg_match('/<style>(.*?)<\/style>/s', $content, $matches)) {
            $styleTag = $matches[0];
            $styleContent = $matches[1];

            // Parse the XML to extract style information
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $result = $dom->loadXML($content);
            libxml_clear_errors();

            if ($result) {
                $xpath = new DOMXPath($dom);

                // Register namespaces
                $xpath->registerNamespace('style', 'urn:oasis:names:tc:opendocument:xmlns:style:1.0');

                // Get automatic styles
                $styleNodes = $xpath->query('//office:automatic-styles/style:style');

                if ($styleNodes && $styleNodes->length > 0) {
                    $css = "";
                    foreach ($styleNodes as $styleNode) {
                        // Your existing code here
                        $styleName = $styleNode->getAttribute("style:name");
                        $styleFamily = $styleNode->getAttribute("style:family");

                        if ($styleFamily === 'paragraph') {
                            $css .= ".p-$styleName {\n";

                            // Extract paragraph properties
                            $paragraphProps = $xpath->query('.//style:paragraph-properties', $styleNode);
                            if ($paragraphProps->length > 0) {
                                $margin = $paragraphProps->item(0)->getAttribute('fo:margin-left');
                                if ($margin) {
                                    $css .= "  margin-left: {$margin};\n";
                                }

                                $textIndent = $paragraphProps->item(0)->getAttribute('fo:text-indent');
                                if ($textIndent) {
                                    $css .= "  text-indent: $textIndent;\n";
                                }
                            }

                            // Extract text properties
                            $textProps = $xpath->query('.//style:text-properties', $styleNode);
                            if ($textProps->length > 0) {
                                $fontWeight = $textProps->item(0)->getAttribute('fo:font-weight');
                                if ($fontWeight === 'bold') {
                                    $css .= "  font-weight: bold;\n";
                                }

                                $fontStyle = $textProps->item(0)->getAttribute('fo:font-style');
                                if ($fontStyle === 'italic') {
                                    $css .= "  font-style: italic;\n";
                                }

                                $fontSize = $textProps->item(0)->getAttribute('fo:font-size');
                                if ($fontSize) {
                                    $css .= "  font-size: $fontSize;\n";
                                }
                            }

                            $css .= "}\n";
                        }

                        if ($styleFamily === 'text') {
                            $css .= ".t-{$styleName} {\n";

                            // Extract text properties
                            $textProps = $xpath->query('.//style:text-properties', $styleNode);
                            if ($textProps->length > 0) {
                                $fontWeight = $textProps->item(0)->getAttribute('fo:font-weight');
                                if ($fontWeight === 'bold') {
                                    $css .= "  font-weight: bold;\n";
                                }

                                $fontStyle = $textProps->item(0)->getAttribute('fo:font-style');
                                if ($fontStyle === 'italic') {
                                    $css .= "  font-style: italic;\n";
                                }
                            }

                            $css .= "}\n";
                        }
                    }

                    $content = str_replace($styleTag, "<style>\n$css</style>", $content);

                } else {
                    // Handle the case where no style nodes were found
                    $css = "/* No styles found */";
                }
            } else {
                // Document failed to load - consider alternative approach
                // Maybe try loadHTML or log an error
                $css = "/* Failed to parse document */";
            }

        }
        // Pass to next handler
        return parent::handle($content, $zip, $images);
    }
}