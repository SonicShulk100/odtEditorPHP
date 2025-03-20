<?php
$idFichier = $_GET['idFichier'] ?? null;

if ($idFichier) {
    $fichier = FichierDAO::getFichierById($idFichier);
}
?>

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
            <!-- Changed from div to textarea -->
            <textarea name="contenuFichier" id="contenuFichier">
                <?php echo htmlspecialchars($fichier->getContenu()); ?>
            </textarea>

            <br>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/jodit@latest/es2021/jodit.fat.min.js"></script>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const editor = new Jodit("#contenuFichier", {
                        uploader: {
                            insertImageAsBase64URI: true
                        },
                        toolbarAdaptive: false,
                        toolbarSticky: false,
                        toolbarButtonSize: "large",
                        toolbarButtonIcons: {
                            more: "⋮"
                        },
                        buttons: "source,|,bold,strikethrough,underline,italic,|,superscript,subscript,|,ul,ol,|,outdent,indent,|,font,fontsize,brush,paragraph,|,image,video,table,link,|,align,undo,redo,|,hr,symbol,fullsize"
                    });
                    editor.buildToolbar();
                });
            </script>

            <br>

            <button type="submit" class="button" id="enregistrer">Enregistrer les modifications</button>
            <button type="submit" class="button" id="annuler">Annuler</button>
        </form>
    </section>
    <?php require_once "views/bas.php"; ?>
</div>
