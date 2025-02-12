<?php

// Importation de la classe mère.
require_once 'utils/Handler.php';

class ImageHandler extends Handler {
    /**
     * Gère la conversion des images
     * @param $content
     * @param ZipArchive $zip
     * @param $images
     * @return array|mixed|string|string[]|null la nouvelle valeur de content
     */
    public function handle($content, ZipArchive $zip, &$images): mixed
    {
        // Handle image conversion
        // Extract images from the ODT content and encode them as base64
        $content = preg_replace_callback('/<draw:image[^>]+xlink:href="([^\"]+)"[^>]*>/',
            static function($matches) use ($zip) {

                $imageSrc = 'Pictures/' . basename($matches[1]);
                $imageData = $zip->getFromName($imageSrc);

                // Vérifier si l'image est trouvée dans le fichier ZIP
                if ($imageData !== false) {
                    $base64Image = base64_encode($imageData);
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mimeType = $finfo->buffer($imageData);

                    return '<img src="data:' . $mimeType . ';base64,' . $base64Image . '" />';
                }

                // Si l'image n'est pas trouvée, on garde la référence d'origine
                return '<img src="' . htmlspecialchars($matches[1]) . '" />';
            }, $content);

        // Appel du handler parent si nécessaire
        return parent::handle($content, $zip, $images) ?? $content;
    }
}
