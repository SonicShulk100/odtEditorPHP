<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'models/DAO/FichierDAO.php';
require 'vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function fichierUpload(): void {
    $db = new PDO(Param::DSN, Param::USER, Param::PASS);
    if (!estConnecte()) {
        header('Location: index.php?action=connecter');
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["fileUpload"])) {
        $nomFichier = $_FILES["fileUpload"]["name"];
        $fichierTemp = $_FILES["fileUpload"]["tmp_name"];
        $idUtilisateur = $_SESSION['idUtilisateur'] ?? null;

        if ($fichierTemp) {
            try {
                $contenuHTML = extractOdtContent($fichierTemp);
                $fichierBinaire = file_get_contents($fichierTemp);

                FichierDAO::createFichier($nomFichier, $contenuHTML, $idUtilisateur, $fichierBinaire);
                header("Location: index.php?action=utilisateur");
                exit();
            } catch (Exception $e) {
                error_log("Erreur lors du traitement du fichier ODT: " . $e->getMessage());
                echo "<p>Erreur lors du traitement du fichier : " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
        echo "<p>Erreur lors de l'upload du fichier.</p>";
    }
    include "views/fichierUpload/vueFichierUpload.php";
}

/**
 * @param $filePath
 * @return string le HTML
 */
function extractOdtContent($filePath): string
{
    if (!file_exists($filePath)) {
        throw new RuntimeException("Fichier introuvable");
    }

    $zip = new ZipArchive();
    if ($zip->open($filePath) !== true) {
        throw new RuntimeException("Impossible d'ouvrir le fichier ODT");
    }

    $contentXml = $zip->getFromName('content.xml');
    $stylesXml = $zip->getFromName('styles.xml');
    $manifestXml = $zip->getFromName('META-INF/manifest.xml');

    if (!$contentXml || !$stylesXml) {
        $zip->close();
        throw new RuntimeException("Fichiers XML requis introuvables dans l'ODT");
    }

    $imageMapping = extractOdtImages($zip, 'uploads/images/');
    $styles = parseOdtStyles($stylesXml);
    $html = convertOdtToHtml($contentXml, $styles, $imageMapping);

    $zip->close();
    return $html;
}

/**
 * @param $stylesXml
 * @return array
 */
function parseOdtStyles($stylesXml): array
{
    $styles = [];
    $dom = new DOMDocument();
    $dom->loadXML($stylesXml);
    $xpath = new DOMXPath($dom);

    $xpath->registerNamespace('style', 'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
    $xpath->registerNamespace('fo', 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0');

    // Styles automatiques et communs
    $styleNodes = $xpath->query('//style:style');
    if ($styleNodes === false) {
        return $styles;
    }

    foreach ($styleNodes as $styleNode) {
        if (!$styleNode instanceof DOMElement) {
            continue;
        }

        $styleName = $styleNode->getAttribute('style:name');
        if (empty($styleName)) {
            continue;
        }

        $styles[$styleName] = [
            'family' => $styleNode->getAttribute('style:family'),
            'parent' => $styleNode->getAttribute('style:parent-style-name'),
            'properties' => []
        ];

        // Propriétés du texte
        $textProps = $xpath->query('.//style:text-properties', $styleNode);
        if ($textProps !== false && $textProps->length > 0) {
            $node = $textProps->item(0);
            if ($node instanceof DOMElement) {
                $styles[$styleName]['properties']['text'] = [
                    'font-weight' => $node->getAttribute('fo:font-weight') ?: null,
                    'font-size' => $node->getAttribute('fo:font-size') ?: null,
                    'font-style' => $node->getAttribute('fo:font-style') ?: null,
                    'color' => $node->getAttribute('fo:color') ?: null,
                    'background-color' => $node->getAttribute('fo:background-color') ?: null,
                    'text-decoration' => $node->getAttribute('style:text-decoration') ?: null
                ];
            }
        }

        // Propriétés du paragraphe
        $paraProps = $xpath->query('.//style:paragraph-properties', $styleNode);
        if ($paraProps !== false && $paraProps->length > 0) {
            $node = $paraProps->item(0);
            if ($node instanceof DOMElement) {
                $styles[$styleName]['properties']['paragraph'] = [
                    'text-align' => $node->getAttribute('fo:text-align') ?: null,
                    'margin-left' => $node->getAttribute('fo:margin-left') ?: null,
                    'margin-right' => $node->getAttribute('fo:margin-right') ?: null,
                    'margin-top' => $node->getAttribute('fo:margin-top') ?: null,
                    'margin-bottom' => $node->getAttribute('fo:margin-bottom') ?: null,
                    'line-height' => $node->getAttribute('fo:line-height') ?: null
                ];
            }
        }
    }

    return $styles;
}

function convertOdtToHtml($contentXml, $styles, $imageMapping): string
{
    $dom = new DOMDocument();
    $dom->loadXML($contentXml);
    $xpath = new DOMXPath($dom);

    $xpath->registerNamespace('text', 'urn:oasis:names:tc:opendocument:xmlns:text:1.0');
    $xpath->registerNamespace('draw', 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0');
    $xpath->registerNamespace('table', 'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
    $xpath->registerNamespace('style', 'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
    $xpath->registerNamespace('office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');

    $html = '<div class="odt-content">';

    $bodyQuery = $xpath->query('//office:body//office:text');
    if ($bodyQuery === false || $bodyQuery->length === 0) {
        return $html . '</div>';
    }

    $body = $bodyQuery->item(0);
    if (!$body instanceof DOMElement) {
        return $html . '</div>';
    }

    foreach ($body->childNodes as $node) {
        if (!$node instanceof DOMElement) {
            continue;
        }

        switch ($node->nodeName) {
            case 'text:h':
                $level = $node->getAttribute('text:outline-level');
                if (empty($level)) $level = 1;
                $styleName = $node->getAttribute('text:style-name');
                $styleAttr = generateStyleAttribute($styles, $styleName);
                $html .= sprintf('<h%d style="%s">%s</h%d>',
                    $level,
                    $styleAttr,
                    processTextContent($node, $styles),
                    $level
                );
                break;

            case 'text:p':
                $styleName = $node->getAttribute('text:style-name');
                $styleAttr = generateStyleAttribute($styles, $styleName);
                $html .= sprintf('<p style="%s">%s</p>',
                    $styleAttr,
                    processTextContent($node, $styles)
                );
                break;

            case 'table:table':
                $html .= processTable($node, $xpath, $styles);
                break;

            case 'draw:frame':
                $html .= processImage($node, $imageMapping);
                break;
        }
    }

    $html .= '</div>';
    return $html;
}

function processTextContent(DOMNode $node, array $styles): string
{
    $content = '';
    if (!$node->hasChildNodes()) {
        return $content;
    }

    foreach ($node->childNodes as $child) {
        if ($child->nodeType === XML_TEXT_NODE) {
            $content .= htmlspecialchars($child->nodeValue ?? '');
        } elseif ($child instanceof DOMElement && $child->nodeName === 'text:span') {
            $styleName = $child->getAttribute('text:style-name');
            $styleAttr = generateStyleAttribute($styles, $styleName);
            $content .= sprintf('<span style="%s">%s</span>',
                $styleAttr,
                processTextContent($child, $styles)
            );
        }
    }
    return $content;
}

function processTable(DOMNode $table, DOMXPath $xpath, array $styles): string
{
    $html = '<table class="odt-table" style="border-collapse: collapse; width: 100%;">';

    $rows = $xpath->query('.//table:table-row', $table);
    if ($rows === false) {
        return $html . '</table>';
    }

    foreach ($rows as $row) {
        if (!$row instanceof DOMElement) {
            continue;
        }

        $html .= '<tr>';
        $cells = $xpath->query('.//table:table-cell', $row);
        if ($cells !== false) {
            foreach ($cells as $cell) {
                if (!$cell instanceof DOMElement) {
                    continue;
                }

                $styleName = $cell->getAttribute('table:style-name');
                $styleAttr = generateStyleAttribute($styles, $styleName);
                $colspan = $cell->getAttribute('table:number-columns-spanned');
                $rowspan = $cell->getAttribute('table:number-rows-spanned');

                $html .= sprintf('<td style="%s" colspan="%d" rowspan="%d">%s</td>',
                    $styleAttr,
                    empty($colspan) ? 1 : (int)$colspan,
                    empty($rowspan) ? 1 : (int)$rowspan,
                    processTextContent($cell, $styles)
                );
            }
        }
        $html .= '</tr>';
    }

    $html .= '</table>';
    return $html;
}

function processImage(DOMNode $frame, array $imageMapping): string
{
    if (!$frame instanceof DOMElement) {
        return '';
    }

    $images = $frame->getElementsByTagName('draw:image');
    if ($images->length === 0) {
        return '';
    }

    $image = $images->item(0);
    if (!$image instanceof DOMElement) {
        return '';
    }

    $href = $image->getAttribute('xlink:href');
    if (empty($href) || !isset($imageMapping[$href])) {
        return '';
    }

    $width = $frame->getAttribute('svg:width') ?: 'auto';
    $height = $frame->getAttribute('svg:height') ?: 'auto';

    return sprintf('<img src="%s" alt="Image ODT" style="width: %s; height: %s;">',
        htmlspecialchars($imageMapping[$href]),
        $width,
        $height
    );
}

function generateStyleAttribute($styles, $styleName): string
{
    if (empty($styleName) || !isset($styles[$styleName])) {
        return '';
    }

    $css = [];
    $style = $styles[$styleName];

    // Propriétés du texte
    if (!empty($style['properties']['text'])) {
        foreach ($style['properties']['text'] as $property => $value) {
            if (!empty($value)) {
                $css[] = "$property: $value";
            }
        }
    }

    // Propriétés du paragraphe
    if (!empty($style['properties']['paragraph'])) {
        foreach ($style['properties']['paragraph'] as $property => $value) {
            if (!empty($value)) {
                $css[] = "$property: $value";
            }
        }
    }

    // Styles hérités
    if (!empty($style['parent']) && isset($styles[$style['parent']])) {
        $css[] = generateStyleAttribute($styles, $style['parent']);
    }

    return implode('; ', array_filter($css));
}
