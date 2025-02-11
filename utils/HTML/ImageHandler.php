<?php

//Importation de la classe mère.
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
        $content = preg_replace_callback('/<draw:image[^>]+xlink:href="([^"]+)"[^>]*>/', static function($matches) use ($zip) {
            $imageSrc = '/Pictures/' . basename($matches[1]);
            $imageData = $zip->getFromName($imageSrc);
            // If the image is found in the ZIP archive, encode it as base64
            if ($imageData !== false) {
                $base64Image = base64_encode($imageData);
                $mimeType = mime_content_type($imageSrc);
                return '<img src="data:' . $mimeType . ';base64,' . $base64Image . '" />';
            }

            // If the image is not found in the ZIP archive, return the original image source
            return '<img src="' . $imageSrc . '" />';
        }, $content);

        // Call the parent handler
        return parent::handle($content, $zip, $images) ?? $content;
    }
}
