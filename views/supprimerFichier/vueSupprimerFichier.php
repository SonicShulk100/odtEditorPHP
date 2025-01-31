<div class="container">
    <nav>
        <?php require_once "views/haut.php"; ?>
    </nav>
    <section>
        <form action="/index.php?action=supprimerFichier1" method="post">
            <h2>Souhaitez-vous vraiment supprimer le fichier ODT choisi?</h2>
            <br>
            <input type="submit" class="button" value="NON" id="refus"/>
            <input type="submit" class="button" value="OUI" id="validation"/>
        </form>
    </section>
    <?php require_once "views/bas.php"; ?>
</div>
