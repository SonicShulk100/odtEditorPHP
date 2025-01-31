<?php

//Mise en place des erreurs.
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Importations des fichiers PHP nécessaires.
require_once 'models/DAO/FichierDAO.php';
require 'vendor/autoload.php';

//Vérification de l'existence de la session ↔ S'il n'existe pas de session...
if (session_status() === PHP_SESSION_NONE) {
    //Alors, on en crée une.
    session_start();
}

/**
 * Ici, c'est le contrôleur qui gère les importations de fichiers ODT (OpenDocument).
 * @return void Le contrôleur est sous-obligation de ne rien retourner.
 */
function fichierUpload(): void {
    //Instanciation d'un objet PDO (Je ne pense pas que c'est nécessaire, mais ça marche quand-même).
    $db = new PDO(Param::DSN, Param::USER, Param::PASS);

    //Si on n'est pas connecté
    if (!estConnecte()) {
        //Alors on se met dans la page de connexion.
        header('Location: index.php?action=connecter');
        exit();
    }

    //Par contre, si on a bien récupéré la méthode POST et on a récupéré le fichier ODT...
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["fileUpload"])) {
        //On récupère le nom du fichier et l'ID de l'utilisateur.
        $nomFichier = $_FILES["fileUpload"]["name"];
        $fichierTemp = $_FILES["fileUpload"]["tmp_name"];
        $diUtilisateur = $_SESSION["idUtilisateur"];

        if ($fichierTemp){
            //On fait un Try-Catch pour gérer les erreurs.
            try{
                //On récupère le contenu HTML à partir du fichier temporaire, et le fichier binaire.
                $contenuHTML = convertOdtToHtmlSimplified($fichierTemp);
                $fichierBinaire = file_get_contents($fichierTemp);

                //On crée une occurence basée sur le nom du fichier, l'ID de l'utilisateur, le contenu HTML et le fichier binaire.
                $response = FichierDAO::createFichier($nomFichier, $contenuHTML, $diUtilisateur, $fichierBinaire);

                //Si on a bien créé une occurence...
                if($response){
                    //Alors, on se dirige dns la page de l'utilisateur en question.
                    header("Location: index.php?action=utilisateur");
                    exit();
                }

            }
            catch(Exception $e){
                die(htmlspecialchars($e->getMessage()));
            }
        }

        //Sinon, on affiche une erreur pour l'importation du fichier.
        echo "<p>Erreur lors de l'upload du fichier.</p>";
    }

    //On inclut la vue dans le contrôleur.
    include "views/fichierUpload/vueFichierUpload.php";
}

/**
 * Ici, on fait une simplification de la conversion ODT ves HTML.
 * @param $odtPath string fichier ODT.
 * @return string le fichier ODT converti en HTML
 */
function convertOdtToHtmlSimplified(string $odtPath): string {
    $zip = new ZipArchive();
    if ($zip->open($odtPath) !== true) {
        throw new RuntimeException("Impossible d'ouvrir le fichier ODT");
    }

    // Extraction des fichiers XML nécessaires
    $contentXml = simplexml_load_string($zip->getFromName('content.xml'));
    $stylesXml = simplexml_load_string($zip->getFromName('styles.xml'));

    //Si on n'a pas les deux fichiers XML
    if (!$contentXml || !$stylesXml) {
        throw new RuntimeException("Impossible de charger les fichiers XML");
    }

    // Espaces de noms
    $namespaces = $contentXml->getNamespaces(true);

    // Mapping des styles
    $stylesMap = [];
    foreach ($stylesXml->xpath('//style:style') as $style) {
        $name = (string) $style['name'];

        $Weight = $style->xpath('.//style:text-properties/@fo:font-weight');
        $Size = $style->xpath('.//style:text-properties/@fo:font-size');
        $Color = $style->xpath('.//style:text-properties/@fo:color');
        $Textalign = $style->xpath('.//style:paragraph-properties/@fo:text-align');

        $stylesMap[$name] = [
            'font-weight' => ($val = reset($Weight)) ? (string) $val : '',
            'font-size'   => ($val = reset($Size)) ? (string) $val : '',
            'color'       => ($val = reset($Color)) ? (string) $val : '',
            'text-align'  => ($val = reset($Textalign)) ? (string) $val : ''
        ];
    }

    // Traitement du contenu principal
    $html = '<div class="odt-content">';

    foreach ($contentXml->xpath('//text:p | //text:h | //draw:frame') as $node) {
        $nodeName = $node->getName();
        $styleName = (string) $node['text:style-name'];
        $styleAttr = generateStyleAttribute($stylesMap, $styleName);

        if ($nodeName === 'h') {
            $level = (int) $node['text:outline-level'] ?: 1;
            $html .= "<h{$level} style='{$styleAttr}'>" . htmlspecialchars((string) $node) . "</h{$level}>";
        } elseif ($nodeName === 'p') {
            $html .= "<p style='{$styleAttr}'>" . htmlspecialchars((string) $node) . "</p>";
        } elseif ($nodeName === 'frame') {
            // Extraction des images
            $image = $node->children($namespaces['draw'])->image;
            if ($image) {
                $href = (string) $image->attributes($namespaces['xlink'])['href'];
                if (str_starts_with($href, 'Pictures/')) {
                    $imagePath = saveImageFromZip($zip, $href);
                    $html .= "<img src='{$imagePath}' alt='Image ODT' style='max-width:100%;'/>";
                }
            }
        }
    }

    //On ferme le premier <div>
    $html .= '</div>';
    $zip->close();
    return $html;
}

/**
 * Génère les attributs CSS en fonction des styles extraits
 */
function generateStyleAttribute(array $styles, string $styleName): string {
    if (!isset($styles[$styleName])) {
        return '';
    }

    $css = [];
    foreach ($styles[$styleName] as $property => $value) {
        if (!empty($value)) {
            $css[] = "{$property}: {$value}";
        }
    }

    return implode('; ', $css);
}

/**
 * Sauvegarde une image extraite d'un fichier ODT
 * @param ZipArchive $zip le gérant.
 * @param string $fileName le nom du fichier ODT.
 * @return string le nouveau chemin du fichier.
 */
function saveImageFromZip(ZipArchive $zip, string $fileName): string {
    $outputDir = 'uploads/images/';
    if (!is_dir($outputDir) && !mkdir($outputDir, 0777, true) && !is_dir($outputDir)) {
        throw new RuntimeException(sprintf('Directory "%s" was not created', $outputDir));
    }

    $imageContent = $zip->getFromName($fileName);
    if (!$imageContent) {
        return '';
    }

    $newFileName = uniqid('img_', true) . '_' . basename($fileName);
    $newFilePath = $outputDir . $newFileName;

    if (file_put_contents($newFilePath, $imageContent) === false) {
        return '';
    }

    return $newFilePath;
}
