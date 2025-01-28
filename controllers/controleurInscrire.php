<?php
/**
 * Contrôleur entière de l'inscription.
 * @return void Le contrôleur gère l'inscription.
 */
function inscrire(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'] ?? null;
        $prenom = $_POST['prenom'] ?? null;
        $login = $_POST['login'] ?? null;
        $password = $_POST['password'] ?? null;

        if ($nom && $prenom && $login && $password) {
            UtilisateurDAO::createUtilisateur($nom, $prenom, $login, $password);
            header('Location: index.php?action=connecter');
            exit();
        } else {
            $erreur = "Tous les champs doivent être remplis.";
            include "views/inscription/vueInscription.php";
        }
    }

    include "views/inscription/vueInscription.php";
}

