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
            $response = UtilisateurDAO::createUtilisateur($nom, $prenom, $login, $password);

            if ($response) {

                $_SESSION['connecte'] = true;

                $user = UtilisateurDAO::verif($login, $password);

                if($user){
                    $_SESSION['idUtilisateur'] = $user['idUtilisateur'] ?? null;

                    header('Location: /index.php?action=utilisateur');

                    exit();
                }
            }
        }
        else{
            $erreur = "Tous les champs doivent être remplis.";
            include "views/inscription/vueInscription.php";
        }
    }

    include "views/inscription/vueInscription.php";
}

