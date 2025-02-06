<?php

//Importation de la classe mère.
require_once 'utils/Handler.php';

class ImageHandler extends Handler {

    /**
     * Gère la conversion des images
     * @param $content
     * @param $zip
     * @param $images
     * @return array|mixed|string|string[]|null la nouvelle valeur de content
     */
    public function handle($content, $zip, &$images): mixed
    {
        // Ensure the directory for images exists
        $imagesDir = 'images';
        if (!is_dir($imagesDir) && !mkdir($imagesDir, 0777, true) && !is_dir($imagesDir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $imagesDir));
        }

        // Handle image conversion
        // Extract images from the ODT content and store them separately
        $content = preg_replace_callback('/<draw:image[^>]+xlink:href="([^"]+)"[^>]*>/', static function($matches) use ($zip, $imagesDir) {
            $imageSrc = 'Pictures/' . basename($matches[1]);
            $imageData = $zip->getFromName($imageSrc);
            // If the image is found in the ZIP archive, save it to the images directory
            if ($imageData !== false) {
                $imagePath = $imagesDir . '/' . basename($matches[1]);
                file_put_contents($imagePath, $imageData);
                return '<img src="' . $imagePath . '" />';
            }

            // If the image is not found in the ZIP archive, return the original image source
            return '<img src="' . $imageSrc . '" />';
        }, $content);

        // Call the parent handler
        return parent::handle($content, $zip, $images) ?? $content;
    }
}
