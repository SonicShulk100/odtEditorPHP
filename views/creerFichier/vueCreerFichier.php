<div class="container">
    <nav>
        <?php require_once "views/haut.php"; ?>
    </nav>
    <section>
        <h2>Création du fichier ODT : </h2>
        <form action="/index.php?action=enregCreer" method="post">
            <label for="nomFichier">Nom du fichier : </label>
            <input type="text" id="nomFichier" name="nomFichier">

            <br>

            <label for="editionFichier">Edition du fichier : </label>

            <br>

            <textarea name="editionFichier" id="editionFichier"></textarea>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/jodit@latest/es2021/jodit.fat.min.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function(){
                    const editor = new Jodit("#editionFichier", {
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

            <input type="submit" class="button" id="enregistrer" value="Enregistrer"/>
            <input type="submit" class="button" id="annuler" value="Annuler"/>
        </form>
    </section>
    <?php require_once "views/bas.php"; ?>
</div>
