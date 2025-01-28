<?php
/**
 * Controleur de la page de l'utilisateur.
 * @return void
 */
function utilisateur(): void
{
    if (!estConnecte()) {
        header('Location: index.php?action=connecter');
        exit();
    }

    include "views/utilisateur/vueUtilisateur.php";
}

/**
 * Vérifie si l'utilisateur est connecté.
 * @return bool TRUE si connecté, FALSE sinon.
 */
function estConnecte(): bool
{
    return session_status() === PHP_SESSION_ACTIVE &&
        isset($_SESSION['idUtilisateur']) &&
        isset($_SESSION['connecte']) &&
        $_SESSION['connecte'] === true;
}
