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
require_once "controleurSupprimerFichier.php";

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
        fichierUpload();
        break;
    case "enregistrerModification":
        enregistrerModification();
        break;
    case "aPropos":
        aPropos();
        break;
    case "créer":
        creerFichier();
        break;
    case "enregCreer":
        enregCreer();
        break;
    case "contact":
        contact();
        break;
    case 'deconnexion':
        deconnexion();
        break;
    case "supprimerFichier":
        supprimerFichier();
        break;
    case "supprimerFichier1":
        supprimerFichier1();
        break;
}
