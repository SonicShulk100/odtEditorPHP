<?php
require_once "lib/autoloader.php";

if(!isset($_SESSION)){
    session_start();
}

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Editeur de fichier ODT</title>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="styles/styles.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.26.0/ui/trumbowyg.min.css">
        <meta http-equiv="content-security-policy" content=""/>
    </head>
    <body>
        <?php require_once "controllers/controleurPrincipal.php";?>
    </body>
</html>
