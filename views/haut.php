<div class="header">
    <button class="button"><a href="../index.php?action=accueil">Accueil</a></button>
    <button class="button"><a href="../index.php?action=aPropos">A propos</a></button>
    <button class="button"><a href="../index.php?action=conact">Contact</a></button>
    <?php if(isset($_SESSION['connecte']) && $_SESSION['connecte'] === true): ?>
        <button class="button"><a href="../index.php?action=import">Importer un fichier ODT</a></button>
        <button class="button"><a href="../index.php?action=creer">Créer un fichier ODT</a></button>
    <?php else: ?>
        <button class="button"><a href="../index.php?action=inscription">Inscrire</a></button>
        <button class="button"><a href="../index.php?action=connecter">Connecter</a></button>
    <?php endif; ?>
</div>