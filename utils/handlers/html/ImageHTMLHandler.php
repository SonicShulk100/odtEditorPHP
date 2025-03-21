<?php

require_once "utils/handlers/HTMLHandler.php";

class ImageHTMLHandler implements HTMLHandler{
    private ?HTMLHandler $nextHandler = null;

    /**
     * @inheritDoc
     */
    public function setNext(HTMLHandler $handler): HTMLHandler{
        $this->nextHandler = $handler;
        return $handler;
    }

    /**
     * @inheritDoc
     */
    public function handle(string $request, ZipArchive $zip, array $images): string
    {
        $request = preg_replace_callback('/<draw:image[^>]+xlink:href="([^\"]+)"[^>]*>/',
            static function($matches) use ($zip) {

                $imageSrc = 'Pictures/' . basename($matches[1]);


                $imageData = $zip->getFromName($imageSrc);

                // Vérifier si l'image est trouvée dans le fichier ZIP

                if ($imageData !== false) {

                    $base64Image = base64_encode($imageData);

                    $info = new finfo(FILEINFO_MIME_TYPE);

                    $mimeType = $info->buffer($imageData);

                    return '<img src="data:' . $mimeType . ';base64,' . $base64Image . '" />';

                }

                // Si l'image n'est pas trouvée, on garde la référence d'origine

                return '<img src="' . htmlspecialchars($matches[1]) . '" />';

            },
            $request);

        return $this->nextHandler?->handle($request, $zip, $images);
    }
}