<?php

// Mise en place des erreurs.
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Importations des fichiers PHP nécessaires.
require_once 'models/DAO/FichierDAO.php';
require_once 'utils/ODTToFullConverter.php';
require_once "controllers/controleurCreerFichier.php";

// Vérification de l'existence de la session ↔ S'il n'existe pas de session...
if (session_status() === PHP_SESSION_NONE) {
    // Alors, on en crée une.
    session_start();
}

/**
 * Ici, c'est le contrôleur qui gère les importations de fichiers ODT (OpenDocument).
 * @return void Le contrôleur est sous-obligation de ne rien retourner.
 */
function fichierUpload(): void {

    // Si on n'est pas connecté
    if (!estConnecte()) {
        // Alors on se met dans la page de connexion.
        header('Location: index.php?action=connecter');
        exit();
    }

    // Par contre, si on a bien récupéré la méthode POST et on a récupéré le fichier ODT...
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["fileUpload"])) {
        // On récupère le nom du fichier et l'ID de l'utilisateur.
        $nomFichier = $_FILES["fileUpload"]["name"];
        $fichierTemp = $_FILES["fileUpload"]["tmp_name"];
        $idUtilisateur = $_SESSION["idUtilisateur"];

        //Si le fichier temporaire existe...
        if ($fichierTemp && is_uploaded_file($fichierTemp)) {
            // On fait un Try-Catch pour gérer les erreurs.
            try {
                // Lire le contenu réel du fichier avant conversion en binaire
                $contenuFichier = file_get_contents($fichierTemp);

                // Vérifier que le fichier a bien été lu
                if ($contenuFichier === false) {
                    throw new RuntimeException("Erreur lors de la lecture du fichier.");
                }

                // On récupère le contenu HTML et CSS à partir du fichier temporaire
                $converter = new ODTToFullConverter();
                $contenuHTML = $converter->convert($fichierTemp);

                // Convertir le contenu réel du fichier en binaire
                $fichierBinaire = stringToBinary($contenuFichier);

                // On crée une occurrence basée sur le nom du fichier, l'ID de l'utilisateur, le contenu HTML, le CSS et le fichier binaire.
                $response = FichierDAO::createFichier($nomFichier, $contenuHTML, $idUtilisateur, $fichierBinaire);

                // Si on a bien créé une occurrence...
                if ($response) {
                    // Alors, on se dirige dans la page de l'utilisateur en question.
                    header("Location: index.php?action=utilisateur");
                    exit();
                }

            } catch (Exception $e) {
                //Afficher l'erreur.
                die(htmlspecialchars($e->getMessage()));
            }
        }

        // Sinon, on affiche une erreur pour l'importation du fichier.
        echo "<p>Erreur lors de l'upload du fichier.</p>";
    }

    // On inclut la vue dans le contrôleur.
    include "views/fichierUpload/vueFichierUpload.php";
}
