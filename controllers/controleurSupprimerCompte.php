<?php
// Affichage des erreurs pour le débogage.
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "models/DAO/UtilisateurDAO.php"; // Inclure la classe contenant deleteUtilisateur()

if (!isset($_SESSION)){
    session_start();
}


function supprimerUtilisateur(): void {
    include "views/supprimerUtilisateur/vueSupprimerCompte.php";
}

function supprimerCompte(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validation'])) {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['idUtilisateur'])) {
            header("Location: index.php?action=connecter");
            exit();
        }

        $idUtilisateur = $_SESSION['idUtilisateur'];

        // Suppression du compte
        if (UtilisateurDAO::deleteUtilisateur($idUtilisateur)) {
            // Détruire la session et rediriger vers l'accueil
            session_destroy();
            header("Location: index.php?action=accueil");
            exit();
        }

        echo "Erreur lors de la suppression du compte.";
    } else {
        // Rediriger vers l'accueil si refus
        header("Location: index.php?action=accueil");
        exit();
    }
}
