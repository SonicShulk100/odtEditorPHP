<?php
require_once "controleurAccueil.php";
require_once "controleurConnexion.php";
require_once "controleurInscrire.php";
require_once "controleurUtilisateur.php";

$action = $_GET['action'] ?? "accueil";

switch($action) {
    case 'accueil':
        accueil();
        break;
    case 'connecter':
        connexion();
        break;
    case 'inscrire':
        inscrire();
        break;
    case "utilisateur":
        utilisateur();
        break;

}
