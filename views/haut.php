<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
?>

<div class="header">
    <button class="button"><a href="../index.php?action=accueil">Accueil</a></button>
    <button class="button"><a href="../index.php?action=aPropos">À propos</a></button>
    <button class="button"><a href="../index.php?action=contact">Contact</a></button>
    <?php if (isset($_SESSION['connecte']) && $_SESSION['connecte'] === true): ?>
        <button class="button"><a href="../index.php?action=importer">Importer un fichier ODT</a></button>
        <button class="button"><a href="../index.php?action=créer">Créer un fichier ODT</a></button>
        <button class="button"><a href="../index.php?action=deconnexion">Déconnexion</a></button>
        <button class="buttin"><a href="../index.php?action=utilisateur">Mon compte</a></button>
    <?php else: ?>
        <button class="button"><a href="../index.php?action=inscription">S'inscrire</a></button>
        <button class="button"><a href="../index.php?action=connecter">Se connecter</a></button>
    <?php endif; ?>
</div>