<?php

require_once "models/DAO/FichierDAO.php";
require_once "models/DTO/Fichier.php";

/**
 * Contrôleur de la création du fichier.
 * @return void
 */
function creerFichier(): void {
    $idUtilisateur = $_SESSION["idUtilisateur"] ?? null;

    if ($idUtilisateur === false) {
        header("Location: index.php?action=utilisateur&erreur=parametres_invalides");
        exit();
    }

    $_SESSION["créationFichier"] = ["idUtilisateur" => $idUtilisateur];

    include "views/creerFichier/vueCreerFichier.php";
}

/**
 * Création d'un fichier via un formulaire.
 * @return void
 */
function enregCreer(): void {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: index.php?action=utilisateur");
        exit();
    }

    $statutAjout = false;
    $messageErreur = "";

    if (isset($_POST["enregistrer"])) {
        $idUtilisateur = filter_input(INPUT_POST, "idUtilisateur", FILTER_VALIDATE_INT);
        $nomFichier = htmlspecialchars($_POST["nomFichier"]);

        if ($idUtilisateur === false || empty($nomFichier)) {
            $messageErreur = "Paramètres invalides";
        } else {
            try {
                $contenuFichier = $_POST["editionFichier"] ?? "";
                $binaire = stringToBinary($contenuFichier);
                $statutAjout = FichierDAO::createFichier($nomFichier, $contenuFichier, $idUtilisateur, $binaire);

                if (!$statutAjout) {
                    $messageErreur = "Échec de la création du fichier";
                }
            } catch (PDOException $e) {
                $messageErreur = "Erreur technique : " . htmlspecialchars($e->getMessage());
            }
        }
    }
    if(isset($_POST["annuler"])){
        header("Location: index.php?action=utilisateur&erreur=Annulation_de_la_creation");
        exit();
    }

    $params = ["création" => $statutAjout ? "success" : "erreur"];
    if (!empty($messageErreur)) {
        $params["erreur"] = htmlspecialchars($messageErreur);
    }

    header("Location: index.php?action=utilisateur&" . http_build_query($params));
    exit();
}

/**
 * Convertit une chaîne en binaire.
 * @param string|null $string
 * @return string
 */
function stringToBinary(?string $string): string {
    //Rien ?
    if ($string === null) {
        //Rien.
        return '';
    }

    //Initialisation du string.
    $binaryString = '';

    //Pour chaque caractère dans le string.
    for ($i = 0, $len = strlen($string); $i < $len; $i++) {
        //On
        $binaryString .= sprintf("%08b ", ord($string[$i]));
    }

    return trim($binaryString);
}
