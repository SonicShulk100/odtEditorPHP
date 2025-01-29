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