<?php

require_once "models/DAO/FichierDAO.php";
require_once "models/DTO/Fichier.php";

/**
 * Le contrôleur de la modification du fichier ODT.
 * @return void Ici, le contrôleur est sous obligation de ne rien retourner.
 */
function modifierFichier(): void{

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        echo "Erreur : ID de fichier invalide.";
        return;
    }

    $idFichier = (int)$_GET['id'];
    $fichier = FichierDAO::getFichierById($idFichier);

    if(!$fichier){
        echo "Erreur : Fichier inexistant.";
        return;
    }

    include "views/modifierFichier/vueModifierFichier.php";
}

function enregistrerModification(): void{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idFichier = (int)$_POST['id'];
        $nomFichier = $_POST['nomFichier'];
        $contenuFichier = $_POST['contenuFichier'];
        $idUtilisateur = (int)$_POST['idUtilisateur'];

        $response = FichierDAO::updateFichier($idFichier, $nomFichier, $contenuFichier, $idUtilisateur);

        if($response){
            header("location: index.php?action=utilisateur");
            exit();
        }
        else{
            echo "<p>Erreur : Fichier inexistant.</p>";
        }
    }
}