<?php
require_once 'models/DAO/FichierDAO.php';

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

/**
 * Contrôleur pour la gestion de l'importation des fichiers.
 * @return void
 */
function fichierUpload(): void {
    // Vérifier si l'utilisateur est connecté
    if (!estConnecte()) {
        header('Location: index.php?action=connecter');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileUpload'])) {
        if ($_FILES['fileUpload']['error'] === UPLOAD_ERR_OK) {
            $tmpFile = $_FILES['fileUpload']['tmp_name'];
            $fileName = $_FILES['fileUpload']['name'];

            // Vérifier l'extension
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if ($extension !== 'odt') {
                echo 'Erreur : Le fichier doit être au format ODT.';
                return;
            }

            // Lire le contenu du fichier ODT
            $content = extractOdtContent($tmpFile);
            if ($content === false) {
                echo 'Erreur : Impossible de lire le contenu du fichier ODT.';
                return;
            }

            // Ajout dans la base de données
            $dateAjout = new DateTime();
            $dateMaj = new DateTime();
            $idUtilisateur = $_SESSION['idUtilisateur']; // Récupérer l'ID de l'utilisateur connecté

            $result = FichierDAO::createFichier($fileName, $content, $dateAjout, $dateMaj, $idUtilisateur);

            if ($result) {
                echo 'Le fichier a été importé avec succès.';
                header('Location: index.php?action=utilisateur');
                exit();
            } else {
                echo 'Erreur : L\'importation a échoué.';
            }
        } else {
            echo 'Erreur : Une erreur est survenue lors du téléchargement.';
        }
    }

    include "views/fichierUpload/vueFichierUpload.php";
}

/**
 * Extraction du contenu d'un fichier ODT.
 * @param string $filePath
 * @return string|false
 */
function extractOdtContent(string $filePath): string|false {
    $zip = new ZipArchive();
    if ($zip->open($filePath) === true) {
        $xmlContent = $zip->getFromName('content.xml');
        $zip->close();

        if ($xmlContent !== false) {
            return strip_tags($xmlContent); // Supprime les balises XML
        }
    }
    return false;
}
