<?php
/**
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
 * Verification si on est bien connecté.
 * @return bool TRUE si on est connecté, FALSE sinon.
 */
function estConnecte(): bool
{
    return isset($_SESSION['idUtilisateur']) && is_numeric($_SESSION['idUtilisateur']);
}
