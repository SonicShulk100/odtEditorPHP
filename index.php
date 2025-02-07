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
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0"/>
        <link rel="stylesheet" href="styles/styles.css"/>
        <link
                href="https://cdn.jsdelivr.net/npm/jodit@latest/es2021/jodit.fat.min.css"
                rel="stylesheet"
                type="text/css"
        />
    </head>
    <body>
        <?php require_once "controllers/controleurPrincipal.php";?>
    </body>
</html>
