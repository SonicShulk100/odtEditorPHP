<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'models/DAO/FichierDAO.php';

require 'vendor/autoload.php'; // Assurer que PhpOffice est bien inclus

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

/**
 * Contrôleur pour la gestion de l'importation des fichiers.
 * @return void
 * @throws \PhpOffice\PhpWord\Exception\Exception
 */
function fichierUpload(): void {
    $db = new PDO(Param::DSN, Param::USER, Param::PASS);
    if (!estConnecte()) {
        header('Location: index.php?action=connecter');
        exit();
    }

    // Vérifier si un fichier a été soumis
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["fileUpload"])) {

        $nomFichier = $_FILES["fileUpload"]["name"];
        $fichierTemp = $_FILES["fileUpload"]["tmp_name"];
        $idUtilisateur = $_SESSION['idUtilisateur'] ?? null; // Récupération de l'ID utilisateur

        if ($fichierTemp) {
            // Extraction du contenu HTML depuis l'ODT et gestion des images
            $contenuXML = extractOdtContent($fichierTemp);
            // Lecture du fichier en binaire
            $fichierBinaire = file_get_contents($fichierTemp);

            // Enregistrement dans la base de données via le DAO
            FichierDAO::createFichier($nomFichier, $contenuXML, $idUtilisateur, $fichierBinaire);

            // Redirection après succès
            header("Location: index.php?action=utilisateur");
            exit();
        } else {
            echo "<p>Erreur lors de l'upload du fichier.</p>";
        }

    }

    include "views/fichierUpload/vueFichierUpload.php";
}

function extractOdtContent($filePath) {
    // Vérification de l'existence du fichier
    if (!file_exists($filePath)) {
        die("Erreur : fichier introuvable !");
    }

    // Ouvrir le fichier ODT comme une archive ZIP
    $zip = new ZipArchive();
    if ($zip->open($filePath) !== true) {
        die("Erreur : Impossible d'ouvrir le fichier ODT !");
    }

    // Lire le fichier content.xml
    $contentXml = $zip->getFromName('content.xml');
    if (!$contentXml) {
        die("Erreur : content.xml introuvable !");
    }

    // Lire et extraire les images
    $imageMapping = extractOdtImages($zip, 'uploads/images/');

    // Transformer le XML en HTML
    $htmlContent = convertOdtXmlToHtml($contentXml, $imageMapping);

    // Fermer l'archive ZIP
    $zip->close();

    return $htmlContent;
}

/**
 * Extrait les images de l'ODT et les enregistre dans un dossier.
 * Retourne un mapping entre les noms d'origine et les nouveaux chemins.
 */
function extractOdtImages(ZipArchive $zip, string $outputDir): array {
    $imageMapping = [];

    // Vérifier et créer le dossier cible si nécessaire
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0777, true);
    }

    // Parcourir les fichiers ZIP et extraire les images
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $fileName = $zip->getNameIndex($i);
        if (str_starts_with($fileName, 'Pictures/')) {
            // Lire le fichier image
            $imageContent = $zip->getFromName($fileName);
            if ($imageContent) {
                $newFilePath = $outputDir . basename($fileName);
                file_put_contents($newFilePath, $imageContent);
                $imageMapping[$fileName] = $newFilePath; // Associer l'ancien chemin au nouveau
            }
        }
    }

    return $imageMapping;
}

/**
 * Convertit le fichier content.xml en HTML tout en remplaçant les images.
 */
function convertOdtXmlToHtml(string $contentXml, array $imageMapping): string {
    $dom = new DOMDocument();
    $dom->loadXML($contentXml);
    $xpath = new DOMXPath($dom);
    $xpath->registerNamespace("text", "urn:oasis:names:tc:opendocument:xmlns:text:1.0");
    $xpath->registerNamespace("draw", "urn:oasis:names:tc:opendocument:xmlns:drawing:1.0");
    $xpath->registerNamespace("table", "urn:oasis:names:tc:opendocument:xmlns:table:1.0");

    $html = "<div>";

    // Extraction des paragraphes (<text:p>)
    foreach ($xpath->query("//text:p") as $paragraph) {
        $html .= "<p>" . htmlentities($paragraph->textContent) . "</p>";
    }

    // Extraction des images (<draw:image>)
    foreach ($xpath->query("//draw:image") as $image) {
        $xlinkHref = $image->getAttribute("xlink:href");
        if (isset($imageMapping[$xlinkHref])) {
            $html .= '<img src="' . $imageMapping[$xlinkHref] . '" alt="Image ODT">';
        }
    }

    // Extraction des tableaux (<table:table>)
    foreach ($xpath->query("//table:table") as $table) {
        $html .= "<table border='1'>";
        foreach ($xpath->query(".//table:table-row", $table) as $row) {
            $html .= "<tr>";
            foreach ($xpath->query(".//table:table-cell", $row) as $cell) {
                $html .= "<td>" . htmlentities($cell->textContent) . "</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</table>";
    }

    $html .= "</div>";

    return $html;
}
