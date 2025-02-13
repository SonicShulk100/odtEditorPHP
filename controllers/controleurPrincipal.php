<?php
//Importation des contrôleurs nécessaires.
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
require_once "controleurSupprimerCompte.php";
require_once "controleurModifierCompte.php";

//Récupération de l'action.
$action = $_GET['action'] ?? "accueil";

//Switch case en fonction de l'action (très, très long, et je ne peux pas le supprimer).
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
    case "supprimer":
        supprimer();
        break;
    case "supprimerUtilisateur":
        supprimerUtilisateur();
        break;
    case "supprimerCompte":
        supprimerCompte();
        break;
    case "modifierCompte":
        modifierCompte();
        break;
    case "enregistrerModificationCompte":
        enregistrerModificationCompte();
        break;
}
