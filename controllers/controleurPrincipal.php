<?php
require_once "controleurAccueil.php";
require_once "controleurConnexion.php";
require_once "controleurInscrire.php";
require_once "controleurUtilisateur.php";
require_once "controleurFichierUpload.php";
require_once "controleurContact.php";

$action = $_GET['action'] ?? "accueil";

switch($action) {
    case 'accueil':
        accueil();
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
    case "contact":
        contact();
        break;
    case 'deconnexion':
        deconnexion();
}
