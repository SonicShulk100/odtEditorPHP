<?php

//Importation de la classe Handler
require_once 'utils/Handler.php';

/**
 * La classe FontHandler permet de gérer la conversion des polices de caractères
 * @extends Handler la classe Handler qui sert à gérer les handlers au cas du pattern "Chaîne de Responsabilité".
 */
class FontHandler extends Handler {
    public function handle($content, $zip, &$images) {
        // Handle font conversion
        // Convert fonts and font sizes from the ODT content to HTML
        $content = preg_replace('/<style:font-face[^>]+style:name="([^"]+)"[^>]*>/', '<span style="font-family:$1;">', $content);
        $content = preg_replace('/<style:text-properties[^>]+fo:font-size="([^"]+)"[^>]*>/', '<span style="font-size:$1;">', $content);

        return parent::handle($content, $zip, $images) ?? $content;
    }
}
