<div class="container">
    <nav>
        <?php require_once "views/haut.php"; ?>
    </nav>
    <section>
        <h2>Modifier le fichier : <?php echo htmlspecialchars($fichier->getNom()); ?></h2>

        <form action="/index.php?action=enregistrerModification" method="post">
            <input type="hidden" name="idFichier" value="<?php echo $fichier->getId(); ?>">

            <label for="nomFichier">Nom du fichier :</label>
            <input type="text" name="nomFichier" id="nomFichier" value="<?php echo htmlspecialchars($fichier->getNom()); ?>" required>

            <label for="contenuFichier">Contenu du fichier :</label>
            <br>
            <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
            <textarea id="contenuFichier" name="contenuFichier"></textarea>
            <script>
                CKEDITOR.replace('contenuFichier');
            </script>
            <button type="submit" class="button">Enregistrer les modifications</button>
        </form>
    </section>
    <?php require_once "views/bas.php"; ?>
</div>
