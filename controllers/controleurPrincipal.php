<?php
require_once "controleurAccueil.php";
require_once "controleurConnexion.php";
require_once "controleurInscrire.php";
require_once "controleurUtilisateur.php";
require_once "controleurFichierUpload.php";
require_once "controleurContact.php";
require_once "controleurModifierFichier.php";
require_once "controleurAPropos.php";
require_once "controleurCreerFichier.php";

$action = $_GET['action'] ?? "accueil";

switch($action) {
    case 'accueil':
        accueil();
        break;
    case 'modifierFichier':
        modifierFichier();
        break;
    case 'connecter':
        connexion();
        break;
    case 'inscription':
        inscrire();
        break;
    case "utilisateur":
        utilisateur();
        break;
    case "importer":
        try {
            fichierUpload();
        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
            die($e->getMessage());
        }
        break;
    case "enregistrerModification":
        enregistrerModification();
        break;
    case "aPropos":
        aPropos();
        break;
    case "créer":
        creer();
        break;
    case "creerFichier":
        creerFichier();
        break;
    case "contact":
        contact();
        break;
    case 'deconnexion':
        deconnexion();
        break;
}
