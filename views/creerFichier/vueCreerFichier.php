<div class="container">
    <nav>
        <?php require_once "views/haut.php"; ?>
    </nav>
    <section>
        <h2>Création du fichier ODT : </h2>
        <form action="/index.php?action=creerFichier" method="post">
            <label for="nomFichier">Nom du fichier : </label>
            <input type="text" id="nomFichier" name="idFichier">

            <br>

            <label for="editionFichier">Edition du fichier : </label>

            <br>

            <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
            <textarea id="editionFichier" name="editionFichier"></textarea>
            <script>
                CKEDITOR.replace('editionFichier');
            </script>

            <br>

            <input type="submit" class="button" id="enregistrer" value="Enregistrer">
            <input type="submit" class="button" id="annuler" value="Annuler">
        </form>
    </section>
    <?php require_once "views/bas.php"; ?>
</div>