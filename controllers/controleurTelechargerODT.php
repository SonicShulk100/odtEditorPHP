<?php
require_once "../models/DAO/FichierDAO.php";
require_once "../vendor/autoload.php"; // Assurez-vous d'utiliser Composer pour PHPWord

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

if (isset($_GET['idFichier'])) {
    $idFichier = (int) $_GET['idFichier'];

    // Récupération du fichier depuis la base de données
    $fichier = FichierDAO::getFichierById($idFichier);

    if ($fichier) {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Ajout du contenu XML dans le document ODT en gérant les namespaces
        $xmlContent = htmlspecialchars_decode($fichier->getContenu());
        $xml = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOCDATA, 'office', true);

        if ($xml === false) {
            die('Erreur de chargement du fichier XML');
        }

        $namespaces = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('office', $namespaces['office']);
        $text = (string) $xml->xpath('//office:body/office:text')[0];

        $section->addText($text);

        // Définition du nom du fichier ODT
        $fileName = "Fichier_" . $fichier->getId() . ".odt";

        // Création du document ODT
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
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
