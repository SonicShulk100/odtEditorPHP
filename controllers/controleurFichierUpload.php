<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'models/DAO/FichierDAO.php';

use PhpOffice\PhpWord\IOFactory;
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
            $contenuHTML = extractOdtContent($fichierTemp);
            // Lecture du fichier en binaire
            $fichierBinaire = file_get_contents($fichierTemp);

            // Enregistrement dans la base de données via le DAO
            FichierDAO::createFichier($nomFichier, $contenuHTML, $idUtilisateur, $fichierBinaire);

            // Redirection après succès
            header("Location: index.php?action=utilisateur");
            exit();
        } else {
            echo "<p>Erreur lors de l'upload du fichier.</p>";
        }

    }

    include "views/fichierUpload/vueFichierUpload.php";
}

/**
 * Extraction du contenu du fichier ODT (y compris images et texte)
 * @param $filePath
 * @return string|void
 */
function extractOdtContent($filePath) {
    try {
        if (!file_exists($filePath)) {
            die("Erreur : fichier introuvable !");
        }

        $phpWord = IOFactory::load($filePath);
        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');

        // Capture de la sortie HTML
        ob_start();
        $htmlWriter->save('php://output');
        $html = ob_get_contents();
        ob_end_clean();

        // Traitement des images et autres éléments (si nécessaire)
        // Exemple de gestion basique pour les images
        return processImagesInHtml($html, $filePath);
    } catch (Exception $e) {
        die("Erreur lors de l'extraction du contenu ODT : " . $e->getMessage());
    }
}

/**
 * Traitement des images dans le fichier ODT
 * Cette fonction prend le contenu HTML et remplace les liens des images par une nouvelle source
 * @param string $html
 * @param string $filePath
 * @return string
 */
function processImagesInHtml(string $html, string $filePath): string
{
    // Extraire les images du fichier ODT
    $zip = new ZipArchive();
    $zip->open($filePath);
    $imageFiles = [];
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $stat = $zip->statIndex($i);
        if (str_starts_with($stat['name'], 'Pictures/')) {
            // Trouver les images dans le répertoire "Pictures" de l'ODT
            $imageFiles[] = $stat['name'];
        }
    }

    // Copier les images extraites vers un dossier spécifique sur le serveur
    $imageDir = 'uploads/images/';
    if (!is_dir($imageDir)) {
        mkdir($imageDir, 0777, true);
    }

    foreach ($imageFiles as $imageFile) {
        $imageContent = $zip->getFromName($imageFile);
        $imageName = basename($imageFile);
        file_put_contents($imageDir . $imageName, $imageContent);

        // Remplacer le lien de l'image dans le HTML
        $html = str_replace('Pictures/' . $imageName, $imageDir . $imageName, $html);
    }

    return $html;
}
