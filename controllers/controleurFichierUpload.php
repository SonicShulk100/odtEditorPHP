<?php

//Mise en place des erreurs.
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Importations des fichiers PHP nécessaires.
require_once 'models/DAO/FichierDAO.php';

//Importations des Handlers
require_once "utils/HTML/FontHandler.php";
require_once "utils/HTML/GlobalXMLHandler.php";
require_once "utils/HTML/ImageHandler.php";
require_once "utils/HTML/ListHandler.php";
require_once "utils/HTML/PageLayoutHandler.php";
require_once "utils/HTML/TableHandler.php";
require_once "utils/HTML/TextFormattingHandler.php";
require_once "utils/HTML/TitleHandler.php";

//Vérification de l'existence de la session ↔ S'il n'existe pas de session...
if (session_status() === PHP_SESSION_NONE) {
    //Alors, on en crée une.
    session_start();
}

/**
 * Ici, c'est le contrôleur qui gère les importations de fichiers ODT (OpenDocument).
 * @return void Le contrôleur est sous-obligation de ne rien retourner.
 */
function fichierUpload(): void {
    //Instanciation d'un objet PDO (Je ne pense pas que c'est nécessaire, mais ça marche quand-même).
    $db = new PDO(Param::DSN, Param::USER, Param::PASS);

    //Si on n'est pas connecté
    if (!estConnecte()) {
        //Alors on se met dans la page de connexion.
        header('Location: index.php?action=connecter');
        exit();
    }

    //Par contre, si on a bien récupéré la méthode POST et on a récupéré le fichier ODT...
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["fileUpload"])) {
        //On récupère le nom du fichier et l'ID de l'utilisateur.
        $nomFichier = $_FILES["fileUpload"]["name"];
        $fichierTemp = $_FILES["fileUpload"]["tmp_name"];
        $diUtilisateur = $_SESSION["idUtilisateur"];

        if ($fichierTemp){
            //On fait un Try-Catch pour gérer les erreurs.
            try{
                //On récupère le contenu HTML à partir du fichier temporaire, et le fichier binaire.
                $contenuHTML = convertOdtToHtmlSimplified($fichierTemp);
                $fichierBinaire = file_get_contents($fichierTemp);

                //On crée une occurence basée sur le nom du fichier, l'ID de l'utilisateur, le contenu HTML et le fichier binaire.
                $response = FichierDAO::createFichier($nomFichier, $contenuHTML, $diUtilisateur, $fichierBinaire);

                //Si on a bien créé une occurence...
                if($response){
                    //Alors, on se dirige dns la page de l'utilisateur en question.
                    header("Location: index.php?action=utilisateur");
                    exit();
                }

            }
            catch(Exception $e){
                die(htmlspecialchars($e->getMessage()));
            }
        }

        //Sinon, on affiche une erreur pour l'importation du fichier.
        echo "<p>Erreur lors de l'upload du fichier.</p>";
    }

    //On inclut la vue dans le contrôleur.
    include "views/fichierUpload/vueFichierUpload.php";
}

/**
 * Ici, on fait une simplification de la conversion ODT vers HTML.
 * @param $odtPath string fichier ODT.
 * @return string le fichier ODT converti en HTML
 */
function convertOdtToHtmlSimplified(string $odtPath): string {
    $zip = new ZipArchive();
    if ($zip->open($odtPath) !== true) {
        throw new RuntimeException("Impossible d'ouvrir le fichier ODT");
    }

    // Extraction du XML complet
    $contentXml = $zip->getFromName('content.xml');
    if (!$contentXml) {
        throw new RuntimeException("Impossible de charger le fichier XML");
    }

    // Création des handlers
    $textHandler = new TextFormattingHandler();
    $listHandler = new ListHandler();
    $tableHandler = new TableHandler();
    $imageHandler = new ImageHandler($zip); // 🔹 Ajout du ZIP à ImageHandler
    $pageLayoutHandler = new PageLayoutHandler();
    $titleHandler = new TitleHandler();
    $fontHandler = new FontHandler();
    $globalHandler = new GlobalXMLHandler(); // 🔹 Ajout du handler global

    // Définition de l'ordre de la chaîne de responsabilité
    $textHandler->setNextHandler($listHandler);
    $listHandler->setNextHandler($tableHandler);
    $tableHandler->setNextHandler($imageHandler);
    $imageHandler->setNextHandler($pageLayoutHandler);
    $pageLayoutHandler->setNextHandler($titleHandler);
    $titleHandler->setNextHandler($fontHandler);
    $fontHandler->setNextHandler($globalHandler); // 🔹 Ajout du Global Handler en dernier

    // Exécution de la chaîne de responsabilité
    $htmlContent = $textHandler->handle($contentXml);

    $zip->close();
    return $htmlContent;
}
