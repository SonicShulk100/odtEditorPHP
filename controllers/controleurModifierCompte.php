<?php

require_once "models/DAO/UtilisateurDAO.php";

/**
 * Contrôleur de la modification du compte.
 * @return void le contrôleur est sous obligation de ne rien retourner.
 */
function modifierCompte(): void{
    include "views/modifierCompte/vueModifierCompte.php";
}

function enregistrerModificationCompte(): void
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["newNom"], $_POST["newPrenom"], $_POST["newMail"], $_POST["newPass"])) {
            $newNom = trim($_POST["newNom"]);
            $newPrenom = trim($_POST["newPrenom"]);
            $newMail = trim($_POST["newMail"]);
            $newPass = trim($_POST["newPass"]);
            $idUtilisateur = $_SESSION["idUtilisateur"] ?? null;

            $response = UtilisateurDAO::updateUtilisateur($idUtilisateur, $newNom, $newPrenom, $newMail, $newPass);

            if ($response) {
                header("Location: index.php?action=utilisateur");
                exit();
            }

            echo "<p>Errur lors de l'enregistrement.</p>";

        } else {
            echo "<p>Données invalides, veuillez vérifier vos informations.</p>";
        }
    }

    header("Location: index.php?action=utilisateur");
    exit();
}