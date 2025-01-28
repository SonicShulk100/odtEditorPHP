<?php

use JetBrains\PhpStorm\NoReturn;

require_once "models/Param.php";

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

/**
 * Contrôleur entière de la connexion
 * @return void Le contrôleur ici gère la connexion dans le site.
 */
function connexion(): void
{

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['login'];
        $password = $_POST['password'];

        // Exemple de vérification dans la base de données
        $utilisateur = UtilisateurDAO::verif($email, $password);

        if ($utilisateur) {
            $_SESSION['connecte'] = true;
            $_SESSION['idUtilisateur'] = $utilisateur['idUtilisateur'];
            header('Location: index.php?action=utilisateur');
            exit();
        } else {
            echo 'Erreur : Identifiants incorrects.';
        }
    }

    include 'views/connexion/vueConnexion.php';
}

/**
 * Contrôleur pour déconnecter l'utilisateur.
 * @return void
 */
#[NoReturn]
function deconnexion(): void
{
    session_start(); // Assurez-vous que la session est démarrée

    // Supprimer toutes les variables de session
    $_SESSION = [];

    // Détruire la session
    session_destroy();

    // Rediriger l'utilisateur vers la page d'accueil ou de connexion
    header('Location: index.php?action=connecter');
    exit();
}

