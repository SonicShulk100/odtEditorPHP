<?php
/**
 * Contrôleur qui gère l'inscription d'un nouvel utilisateur
 * @return void Le contrôleur est sous-obligation de ne rien retourner.
 */
function inscrire(): void
{
    //Vérification si on utilise la méthode HTTP(HyperText Transfer Protocol) POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //Récupération des valeurs dans le formulaire.
        $nom = $_POST['nom'] ?? null;
        $prenom = $_POST['prenom'] ?? null;
        $login = $_POST['login'] ?? null;
        $password = $_POST['password'] ?? null;

        //Si les valeurs ne sont pas vides...
        if ($nom && $prenom && $login && $password) {
            //...Alors on initialise la base de données avec la création d'un nouvel utilisateur.
            $response = UtilisateurDAO::createUtilisateur($nom, $prenom, $login, $password);

            //Si tout est bon...
            if ($response) {

                //Alors on est connecté
                $_SESSION['connecte'] = true;

                //Et on vérifie si l'utilisateur existe bien.
                $user = UtilisateurDAO::verif($login, $password);

                //Si l'utilisateur existe bien.
                if($user){
                    //Alors on se met dans la page de l'utilisateru créé.
                    $_SESSION['idUtilisateur'] = $user['idUtilisateur'] ?? null;

                    header('Location: /index.php?action=utilisateur');

                    exit();
                }
            }
        }
        else{
            //Sinon on affiche l'erreur que toutes les champs doivent être remplis.
            $erreur = "Tous les champs doivent être remplis.";
            include "views/inscription/vueInscription.php";
        }
    }

    //On inclue bien la vue en question.
    include "views/inscription/vueInscription.php";
}

