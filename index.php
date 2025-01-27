<?php

use Random\RandomException;

if(!isset($_SESSION)){
    session_start();
}

try {
    $csrfToken = bin2hex(random_bytes(32));
} catch (RandomException $e) {
    die($e->getMessage());
}

$_SESSION['csrfToken'] = $csrfToken;
?>
<!DOCTYPE html>
<html lang="fr">
    <head>

    </head>
    <body>

    </body>
</html>
