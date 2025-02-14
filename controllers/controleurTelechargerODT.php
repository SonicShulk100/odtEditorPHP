<?php
require_once "../models/DAO/FichierDAO.php";
require_once "../vendor/autoload.php"; // Assurez-vous d'utiliser Composer pour PHPWord

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

if (isset($_GET['idFichier'])) {
    $idFichier = (int) $_GET['idFichier'];

    // Récupération du fichier depuis la base de données
    $fichier = FichierDAO::getFichierById($idFichier);

    if ($fichier) {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Ajout du contenu dans le document ODT
        $section->addText(htmlspecialchars_decode($fichier->getContenu()));

        // Définition du nom du fichier ODT
        $fileName = "Fichier_" . $fichier->getId() . ".odt";

        // Création du document ODT
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename={$fileName}");
        header("Content-Type: application/vnd.oasis.opendocument.text");

        // Enregistrement dans un flux de sortie
        try {
            $objWriter = IOFactory::createWriter($phpWord, 'ODText');
        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
            die($e->getMessage());
        }
        $objWriter->save("php://output");
        exit();
    }

    echo "Fichier introuvable.";
} else {
    echo "ID de fichier invalide.";
}
