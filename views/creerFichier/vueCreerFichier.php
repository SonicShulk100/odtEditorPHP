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

            <script src="https://cdn.jsdelivr.net/npm/jodit@latest/es2021/jodit.fat.min.js"></script>
            <textarea id="editionFichier" name="editionFichier"></textarea>
            <script>
                import Jodit from "jodit";

                document.addEventListener("DOMContentLoaded", function(){
                    /**
                     * Jodit Editor.
                     * @type {Jodit}
                     * @see https://xdsoft.net/jodit/ the official site
                     * @see https://xdsoft.net/jodit/doc/ the official documentation
                     * @see https://xdsoft.net/jodit/doc/classes/Jodit.html the official documentation of the Jodit class
                     * @see https://xdsoft.net/jodit/doc/classes/Config.html the official documentation of the Config class
                     * @see https://xdsoft.net/jodit/doc/classes/ToolbarIcon.html the official documentation of the ToolbarIcon class
                     * @see https://xdsoft.net/jodit/doc/classes/Command.html the official documentation of the Command class
                     * @see https://xdsoft.net/jodit/doc/classes/Widget.html the official documentation of the Widget class
                     * @see https://xdsoft.net/jodit/doc/classes/Plugin.html the official documentation of the Plugin class
                     * @see https://xdsoft.net/jodit/doc/classes/Module.html the official documentation of the Module class
                     * @see https://xdsoft.net/jodit/doc/classes/Component.html the official documentation of the Component class
                     * @see https://xdsoft.net/jodit/doc/classes/Observer.html the official documentation of the Observer class
                     * @see https://xdsoft.net/jodit/doc/classes/Event.html the official documentation of the Event class
                     * @see https://xdsoft.net/jodit/doc/classes/Tools.html the official documentation of the Tools class
                     * @see https://xdsoft.net/jodit/doc/classes/WidgetData.html the official documentation of the WidgetData class
                     * @see https://xdsoft.net/jodit/doc/classes/WidgetData.html the official documentation of the WidgetData class
                     * @see https://xdsoft.net/jodit/doc/classes/WidgetData.html the official documentation of the WidgetData class
                     * @see https://xdsoft.net/jodit/doc/classes/WidgetData.html the official documentation of the WidgetData class
                     */
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
                        buttons: "source,|,bold,strikethrough,underline,italic,|,superscript,subscript,|,ul,ol,|,outdent,indent,|,font,fontsize,brush,paragraph,|,image,video,table,link,|,align,undo,redo,|,hr,symbol,fullsize",
                        events: {
                            "change": function(){
                                document.getElementById("editionFichier").value = this.value;
                            }
                        }
                    });
                    editor.buildToolbar();
                });
            </script>

            <br>

            <input type="submit" class="button" id="enregistrer" value="Enregistrer"/>
            <input type="submit" class="button" id="annuler" value="Annuler"/>
        </form>
    </section>
    <br>
    <br>
    <br>
    <br>
    <br>
    <?php require_once "views/bas.php"; ?>
</div>