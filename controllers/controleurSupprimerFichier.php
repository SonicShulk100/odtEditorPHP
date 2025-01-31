<?php

/**
 * Contrôleur pour la suppression d'un fichier ODT en fonction
 * de l'ID du fichier.
 * @return void le contrôleur est sous obligation de ne rien retourner.
 */
function supprimerFichier(): void{
    include "views/supprimerFichier/vueSupprimerFichier.php";
}

/**
 * Le contrôleur qui utilise le DAO de fichier
 * @return void Comme pour le
 */
function supprimerFichier1(): void{
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        if(isset($_POST["validation"])){

            $idFichier = $_POST["idFichier"];
            $idUtilisateur = $_POST["idUtilisateur"];

            $response = FichierDAO::deleteFichier($idFichier, $idUtilisateur) ?? false;

            if($response){
                echo "<p>Suppression faite.</p>";
                header("Location : index.php?action=utilisateur");
                exit();
            }
            echo "<p>Impossible de supprimer le fichier.</p>";
            header("Location : index.php?action=utilisateur");
            exit();

        }

        if(isset($_POST["refus"])){
            header("Location: index.php?action=utilisateur");
            exit();
        }
    }
}