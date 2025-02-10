<div class="container">
    <nav>
        <?php require_once "views/haut.php"; ?>
    </nav>
    <section>
        <form action="/index.php?action=supprimer" method="post" id="supression">
            <h2>Souhaitez-vous vraiment supprimer le fichier ODT choisi?</h2>
            <br>

            <!-- Utilisation des données de session -->
            <input type="hidden" name="idFichier" value="<?php echo htmlspecialchars($_SESSION['suppression_fichier']['idFichier'] ?? ''); ?>">
            <input type="hidden" name="idUtilisateur" value="<?php echo htmlspecialchars($_SESSION['idUtilisateur'] ?? ''); ?>">

            <button type="submit" class="button" name="validation" value="true">OUI</button>
            <button type="submit" class="button" name="refus" value="true">NON</button>

            <!-- En cas de clique sur un bouton -->
             <script>
                 //Chaque bouton de type "submit"...
                document.querySelectorAll('button[type="submit"]').forEach(button => {
                    //On ajoute un
                    button.addEventListener('click', function(e) {
                        const confirmation = confirm('Êtes-vous sûr de vouloir continuer ?');
                        //Validé ?
                        if (!confirmation && this.name === 'validation') {
                            e.preventDefault();
                        }
                    });
                });
            </script>

        </form>
    </section>
    <?php require_once "views/bas.php"; ?>
</div>
