<?php

class ODTExtractor
{
    /**
     * Extrait les données du fichier ODT et les convertit en HTML.
     * @param string $odtFilePath le fichier ODT à extraire
     * @return string|null le contenu HTML extrait ou NULL en cas d'erreur
     */
    public static function extractContentXML(string $odtFilePath): ?string
    {
        $zip = new ZipArchive();

        if ($zip->open($odtFilePath) !== true) {
            error_log("Error: Could not open ODT file.");
            return null;
        }

        $xml = $zip->getFromName('content.xml');
        $zip->close();

        if ($xml === false) {
            error_log("Error: Could not find content.xml inside ODT file.");
            return null;
        }

        // Load XML safely
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        if (!$doc->loadXML($xml)) {
            error_log("Error: Invalid XML structure in content.xml");
            foreach (libxml_get_errors() as $error) {
                error_log("XML Error: " . $error->message);
            }
            libxml_clear_errors();
            return null;
        }

        // Use XPath to extract data
        $xpath = new DOMXPath($doc);
        $xpath->registerNamespace("text", "urn:oasis:names:tc:opendocument:xmlns:text:1.0");
        $xpath->registerNamespace("draw", "urn:oasis:names:tc:opendocument:xmlns:drawing:1.0");
        $xpath->registerNamespace("table", "urn:oasis:names:tc:opendocument:xmlns:table:1.0");
        $xpath->registerNamespace("xlink", "http://www.w3.org/1999/xlink");

        $htmlContent = "";

        // Extract paragraphs
        $nodes = $xpath->query("//text:p");
        foreach ($nodes as $node) {
            $htmlContent .= "<p>" . self::convertText($node) . "</p>\n";
        }

        // Extract images
        $imageNodes = $xpath->query("//draw:frame/draw:image");
        foreach ($imageNodes as $imageNode) {
            $imageHref = $imageNode->getAttribute("xlink:href");
            $htmlContent .= "<img src='$imageHref' alt='Image' />\n";
        }

        // Extract lists
        $listNodes = $xpath->query("//text:list");
        foreach ($listNodes as $listNode) {
            $htmlContent .= "<ul>\n";
            foreach ($xpath->query("text:list-item", $listNode) as $listItem) {
                $htmlContent .= "<li>" . self::convertText($listItem) . "</li>\n";
            }
            $htmlContent .= "</ul>\n";
        }

        // Extract tables
        $tableNodes = $xpath->query("//table:table");
        foreach ($tableNodes as $tableNode) {
            $htmlContent .= "<table border='1'>\n";

            foreach ($xpath->query("table:table-row", $tableNode) as $row) {
                $htmlContent .= "<tr>\n";
                foreach ($xpath->query("table:table-cell", $row) as $cell) {
                    $htmlContent .= "<td>" . self::convertText($cell) . "</td>\n";
                }
                $htmlContent .= "</tr>\n";
            }
            $htmlContent .= "</table>\n";
        }

        return $htmlContent;
    }

    /**
     * Convertit le nœud XML en texte HTML (Un peu récursif).
     * @param mixed $node le nœud XML à convertir
     * @return string le texte converti en HTML
     */
    private static function convertText(mixed $node): string
    {
        //Instanciation de la variable text
        $text = "";

        //Boucle sur les enfants du nœud
        foreach ($node->childNodes as $child) {
            //Si le nœud est un nœud texte
            if ($child->nodeType === XML_TEXT_NODE) {
                //Ajout du texte au contenu
                $text .= htmlspecialchars($child->nodeValue);
            }
            //Si le nœud est un nœud span
            elseif ($child->nodeName === "text:span") {
                //Récupération du style
                $style = $child->getAttribute("text:style-name");
                $formattedText = self::convertText($child);

                //Application du style
                if (str_contains($style, "T$1")) {
                    $formattedText = "<b>$formattedText</b>";
                }
                if (str_contains($style, "italic")) {
                    $formattedText = "<i>$formattedText</i>";
                }
                if (str_contains($style, "underline")) {
                    $formattedText = "<u>$formattedText</u>";
                }

                $text .= $formattedText;
            }
        }

        //Retour du texte
        return $text;
    }
}
