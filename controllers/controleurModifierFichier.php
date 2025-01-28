<?php

require_once "models/DAO/FichierDAO.php";
require_once "models/DTO/Fichier.php";

/**
 * Le contrôleur de la modification du fichier ODT.
 * @return void Ici, le contrôleur est sous obligation de ne rien retourner.
 */
function modifierFichier(): void{

    //Vérification si l'utilisateur est connecté.
    if(!estConnecte()){
        header("Location:/index.php?action=connecter");
        exit();
    }

    //Récupération de l'ID du fichier.
    $idFichier = $_GET["id"] ? (int)$_GET["id"] : 0;

    //Récupération du fichier avec la DAO en utilisant l'ID du fichier
    $fichier = FichierDAO::getFichierById($idFichier);

    //Si l'ID de l'utilisateur n'est pas le même que celle qui est contenu dans la session.
    //TODO : Modifier la condition.
    if($fichier["idUtilisateur"] !== $_SESSION["iUtilisateur"]){
        header("Location:/index.php?action=utilisateur");
        exit();
    }

    //Si on utilise bien la requête POST
    if($_SERVER["REQUEST_METHOD"] === "POST"){

        //Récupération le nom et le contenu du fichier.
        $nomFichier = $_POST["nomFichier"] ?? "";
        $contenuFichier = $_POST["contenuFichier"] ?? "";

        //On fait une MaJ dans la base de données.
        $success = FichierDAO::updateFichier($idFichier, $nomFichier, $contenuFichier, $_SESSION["iUtilisateur"]);

        //Si c'est un succès
        if($success){
            //On se dirige vers la page de l'utilisateur
            header("Location:/index.php?action=utilisateur");
            exit();
        }
        else{
            //Sinon on affiche l'erreur.
            echo "<p>Erreur : Impossible de mettre à jour le fichier. </p>";
        }
    }

    //Inclusion de la vue correspondante.
    include "views/modifierFichier/vueModifierFichier.php";
}