<div class="container">
    <nav>
        <?php require_once "views/haut.php"; ?>
    </nav>
    <section>
        <form action="/index.php?action=creerFichier" method="post">
            <label for="nomFichier">Nom du fichier : </label>
            <input type="text" id="nomFichier" name="idFichier" required>

            <br>

            <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>

            <label for="editionFichier">Edition du fichier : </label>
            <textarea id="editionFichier" name="editionFichier">Remplacer ce texte par votre modification</textarea>
            <script>
                CKEDITOR.replace("#editionFichier");
            </script>
            <br>
        </form>
    </section>
    <?php require_once "views/bas.php"; ?>
</div>