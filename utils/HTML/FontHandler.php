<?php

//Importation de la classe mère
require_once "utils/XMLHandler.php";

class FontHandler extends XMLHandler
{
    protected function process(string $xml): string
    {
        // Extraction des polices définies dans <office:font-face-decls>
        preg_match_all('/<style:font-face[^>]+style:name="([^"]+)"[^>]+svg:font-family="([^"]+)"[^>]*>/', $xml, $matches, PREG_SET_ORDER);

        $fontCSS = "<style>\n";
        foreach ($matches as $match) {
            $fontName = htmlspecialchars($match[1]);
            $fontFamily = htmlspecialchars($match[2]);

            $fontCSS .= "  @font-face {\n";
            $fontCSS .= "    font-family: '{$fontName}';\n";
            $fontCSS .= "    src: local('{$fontFamily}');\n";
            $fontCSS .= "  }\n";
        }
        $fontCSS .= "</style>\n";

        // Suppression des balises <office:font-face-decls> et <office:scripts/>
        $xml = preg_replace('/<office:font-face-decls[^>]*>.*?<\/office:font-face-decls>/s', '', $xml);
        $xml = preg_replace('/<office:scripts\/>/', '', $xml);

        // Ajout du CSS en haut du HTML généré
        return $fontCSS . $xml;
    }
}
