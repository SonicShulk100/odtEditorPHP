<?php

//Importation de la classe mère
require_once "utils/XMLHandler.php";

class ImageHandler extends XMLHandler
{
    private ZipArchive $zip;

    public function __construct(ZipArchive $zip)
    {
        $this->zip = $zip;
    }

    protected function process(string $xml): string
    {
        preg_match_all('/<draw:image[^>]*xlink:href="([^"]+)"[^>]*>/', $xml, $matches);

        foreach ($matches[1] as $imagePath) {
            if (str_starts_with($imagePath, 'Pictures/')) {
                $newPath = $this->saveImageFromZip($imagePath);
                $xml = str_replace($imagePath, $newPath, $xml);
            }
        }

        return $xml;
    }

    /**
     * Sauvegarde une image extraite d'un fichier ODT
     * @param string $fileName Le nom du fichier image dans l'archive ODT
     * @return string Le chemin d'accès à l'image enregistrée
     */
    private function saveImageFromZip(string $fileName): string
    {
        $outputDir = 'uploads/images/';

        // Vérifier si le dossier existe, sinon le créer
        if (!is_dir($outputDir) && !mkdir($outputDir, 0777, true) && !is_dir($outputDir)) {
            throw new RuntimeException(sprintf('Impossible de créer le dossier "%s"', $outputDir));
        }

        // Récupérer le contenu de l'image depuis l'archive ZIP
        $imageContent = $this->zip->getFromName($fileName);
        if (!$imageContent) {
            return '';
        }

        // Générer un nom de fichier unique
        $newFileName = uniqid('img_', true) . '_' . basename($fileName);
        $newFilePath = $outputDir . $newFileName;

        // Enregistrer l'image sur le serveur
        if (file_put_contents($newFilePath, $imageContent) === false) {
            throw new RuntimeException("Impossible d'enregistrer l'image : $newFilePath");
        }

        return $newFilePath;
    }
}
