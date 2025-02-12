<div class="container">
    <nav>
        <?php require_once "views/haut.php"; ?>
    </nav>
    <section>
        <form action="/index.php?action=supprimerCompte" method="post">
            <h2>Souhaitez-vous vraiment supprimer le compte ?</h2>
            <br>
            <button type="submit" class="button" name="validation" value="true">OUI</button>
            <a href="/index.php?action=accueil" class="button">NON</a>
            <script>
                //Une expérience plus pratique pour l'utilisateur (Pourquoi Javascript ?)
                document.querySelectorAll('button[name="validation"]').forEach(button => {
                    button.addEventListener('click', function(e) {
                        if (!confirm('Êtes-vous sûr de vouloir continuer ?')) {
                            e.preventDefault();
                        }
                    });
                });
            </script>
        </form>
    </section>
    <?php require_once "views/bas.php"; ?>
</div>

