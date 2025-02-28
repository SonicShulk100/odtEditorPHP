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
        <link href="https://cdn.jsdelivr.net/npm/jodit@latest/es2021/jodit.fat.min.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <h1 class="titre">Editeur d'ODT - Un Projet de stage de deuxième année de BTS - SIO Option SLAM</h1>
        <?php require_once "controllers/controleurPrincipal.php";?>
    </body>
</html>

<style>
    .titre{
        justify-content: center;
        font-family: "Consolas", "Courier", "Courier New", monospace;
        text-decoration: underline;
        color: #40195b;
    }
</style>