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


            <input type="hidden" name="idUtilisateur" value="<?php echo htmlspecialchars($_SESSION['idUtilisateur'] ?? ''); ?>"/>

            <script src="https://cdn.jsdelivr.net/npm/jodit@latest/es2021/jodit.fat.min.js"></script>
            <script>
                //Implémentation du composant.
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

            <button type="submit" class="button" id="enregistrer" name="enregistrer" value="true">Enregistrer</button>
            <button type="submit" class="button" id="annuler" name="annuler" value="true">Annuler</button>

            <script>
                //Chaque bouton de type "submit"...
                document.querySelectorAll('button[type="submit"]').forEach(button => {
                    //On ajoute un
                    button.addEventListener('click', function(e) {
                        const confirmation = confirm('Êtes-vous sûr de vouloir continuer ?');
                        //Validé ?
                        if (!confirmation && this.name === 'enregistrer') {
                            e.preventDefault();
                        }
                    });
                });
            </script>
        </form>
    </section>
    <?php require_once "views/bas.php"; ?>
</div>
