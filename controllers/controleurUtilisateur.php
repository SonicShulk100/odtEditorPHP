<?php
/**
 * Controleur de la page de l'utilisateur.
 * @return void le contrôleur est sous-obligation de ne pas retourner quelque chose.
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
    return isset($_SESSION['idUtilisateur'], $_SESSION['connecte'])
        && session_status() === PHP_SESSION_ACTIVE
        && $_SESSION['connecte'] === true;
}
