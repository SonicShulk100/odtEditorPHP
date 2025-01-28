<?php
require_once "lib/autoloader.php";

if(!isset($_SESSION)){
    session_start();
}

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Editeut de fichier ODT</title>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="styles/styles.css"/>
        <meta http-equiv="content-security-policy" content="default-src 'self' script-src 'self'"/>
    </head>
    <body>
        <?php require_once "controllers/controleurPrincipal.php";?>
    </body>
</html>
