<?php

/**
 * Contrôleur de la création du fichier.
 * @return void Le contrôleur est sous-obligation de ne pas retourner quelque chose.
 */
function creer(): void{
    include "views/creerFichier/vueCreerFichier.php";
}

/**
 * Contrôleur pour la création du fichier ODT.
 * @return void Comme la fonction creer(), le contrôleur est sous-obligation de ne pas retourner quelque chose.
 */
function creerFichier(): void{

    //Vérification si la méthode est bien POST
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //Si on a cliqué sur le bouton enregistrer...
        if(isset($_POST["enregistrer"])){

            //Récupération des données contenu dans le Formulaire.
            $nomFichier = $_POST["nomFichier"];
            $contenuFichier = $_POST["editionFichier"];
            $idUtilisateur = $_SESSION["idUtilisateur"] ?? null;
            $binary = stringToBinary($contenuFichier) ?? null;

            //Initialisation de la base de données afin de faire une insertion dans la base de données
            $response = FichierDAO::createFichier($nomFichier, $contenuFichier, $idUtilisateur, $binary);

            //Si c'est fait...
            if($response){
                //Alors on va dans la page de dl'utilisateur.
                echo "<p>Création faite!</p>";
                header("location: ../index.php?action=utilisateur");
                exit();
            }
            else{
                //Sinon on affiche une erreur.
                echo "<p>Erreur!</p>";
            }
        }
        else if(isset($_POST["annuler"])){
            //Sinon, on va vers la page de l'utilisateur.
            header("location: ../index.php?action=utilisateur");
            exit();
        }

    }

    include "views/creerFichier/vueCreerFichier.php";
}

/**
 * Convertir une chaîne de caractères en Binaire.
 * @param string $string la chaîne de caractère en question.
 * @return string la chaîne de caractères convertit en binaire.
 */
function stringToBinary(string $string): string
{
    $characters = str_split($string);

    $binary = [];
    foreach ($characters as $character) {
        $data = unpack('H*', $character);
        $binary[] = base_convert($data[1], 16, 2);
    }

    return implode(' ', $binary);
}
