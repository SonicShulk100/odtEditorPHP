<?php
require_once "models/Param.php";

/**
 * Contrôleur entière de la connexion
 * @return void Le contrôleur ici gère la connexion dans le site.
 */
function connexion(): void
{
    //Si la méthode est POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //Alors on prend le login et le password.
        $login = $_POST['login'] ?? null;
        $password = $_POST['password'] ?? null;

        //Si les deux ne sont pas vides...
        if ($login && $password) {
            //Mise en relation de la base de données avec l'obet UtilisateurDAO.
            $user = UtilisateurDAO::verif($login, $password);

            //Si c'est TRUE
            if ($user) {
                $_SESSION['idUtilisateur'] = $user['idUtilisateur']; // Stocker l'ID de l'utilisateur
                header('Location: index.php?action=utilisateur');
                exit();
            } else {
                $erreur = "Nom d'utilisateur ou mot de passe incorrect.";
                include "views/connexion/vueConnexion.php";
            }
        } else {
            $erreur = "Veuillez remplir tous les champs.";
            include "views/connexion/vueConnexion.php";
        }
    }

    include "views/connexion/vueConnexion.php";
}
