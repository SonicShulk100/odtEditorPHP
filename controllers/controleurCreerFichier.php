<?php

// Require the necessary files.
// The require_once statement is identical to require except PHP will check if the file has already been included, and if so, not include (require) it again.
require_once "models/DAO/FichierDAO.php";
require_once "models/DTO/Fichier.php";

/**
 * Contrôleur de la création du fichier.
 * @return void Le contrôleur est sous-obligation de ne pas retourner quelque chose.
 */
function creerFichier(): void{
    include "views/creerFichier/vueCreerFichier.php";
}

/**
 * for the creation of a file with the form.
 * @return void
 */
function enregCreer(): void
{
    // Check if the request method is POST.
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Forcez la synchronisation des données
        $_POST['editionFichier'] = isset($_POST['editionFichier']) ?
            htmlspecialchars($_POST['editionFichier'], ENT_QUOTES) : '';

        // Get the name of the file.
        $nomFichier = $_POST['nomFichier'] ?? '';
        $contenuFichier = $_POST['editionFichier'];
        $idUtilisateur = $_SESSION['idUtilisateur'];

        // Check if the file name is not empty.
        if (!empty($nomFichier)) {
            // Convert the content of the file to binary.
            $binary = stringToBinary($contenuFichier);
            $response = FichierDAO::createFichier($nomFichier, $contenuFichier, $idUtilisateur, $binary);

            // Check if the response is true.
            if ($response) {
                // Redirect to the user page.
                header("location: ../index.php?action=utilisateur");
                exit();
            }
        } else {
            // Redirect to the creation page with an error message.
            header("location: /index.php?action=creer&erreur=nom_vide");
            exit();
        }
    }
}

/**
 * Convertir une chaîne de caractères en Binaire.
 * @param string|null $string la chaîne de caractère en question.
 * @return string la chaîne de caractères convertit en binaire.
 */
function stringToBinary(?string $string): string
{
    // Split the string into an array of characters.
    $characters = str_split($string);

    // Convert each character to binary.
    $binary = [];

    // Loop through each character.
    foreach ($characters as $character) {
        // Unpack the character.
        $data = unpack('H*', $character);
        // Convert the character to binary.
        $binary[] = base_convert($data[1], 16, 2);
    }

    // Return the binary string.
    return implode(' ', $binary);
}
