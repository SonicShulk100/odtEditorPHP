<?php

//Importation des DAO nécessaires.
require_once "models/DAO/FichierDAO.php";
require_once "models/DTO/Fichier.php";

/**
 * Le contrôleur de la modification du fichier ODT.
 * @return void Ici, le contrôleur est sous obligation de ne rien retourner.
 */
function modifierFichier(): void{

    //Pas d'ID de fichier?
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

        //Affichage de l'erreur.
        echo "Erreur : ID de fichier invalide.";
        return;
    }

    //Récupération du fichier par l'ID.
    $idFichier = (int)$_GET['id'];
    $fichier = FichierDAO::getFichierById($idFichier);

    //Pas de fichier?
    if(!$fichier){
        //Afficher l'erreur.
        echo "Erreur : Fichier inexistant.";
        return;
    }

    //Inclure la vue de modification.
    include "views/modifierFichier/vueModifierFichier.php";
}


/**
 * Enregistrement des modifications faites pour un fichier.
 * @return void Comme la fonction "modifierFichier", cette fonction est sous obligation de ne rien retourner.
 */
function enregistrerModification(): void{

    //Vérification de l'utilisation de la méthode POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //Récupération de l'ID, du nom, du contenu et créateur du fichier.
        $idFichier = (int)$_POST['id'];
        $nomFichier = $_POST['nomFichier'];
        $contenuFichier = $_POST['contenuFichier'];
        $idUtilisateur = (int)$_POST['idUtilisateur'];

        //Utilisation de la DAO pour la mise à jour du fichier.
        $response = FichierDAO::updateFichier($idFichier, $nomFichier, $contenuFichier, $idUtilisateur);

        //Si tout va bien...
        if($response){
            //Alors, on se dirige vers la page de l'utilisateur.
            header("location: index.php?action=utilisateur");
            exit();
        }

        //Sinon, on affiche l'erreur indiquant le fichier n'existe pas.
        echo "<p>Erreur : Fichier inexistant.</p>";
    }
}
