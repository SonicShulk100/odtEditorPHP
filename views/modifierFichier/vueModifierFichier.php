<div class="container">
    <nav>
        <?php require_once "views/haut.php"; ?>
    </nav>
    <section>
        <?php if (isset($fichier)):?>
            <h1>Modifier le fichier : <?= htmlspecialchars($fichier->getNom());?></h1>
            <form action="/index.php?action=modifierFichier&id=<?= htmlspecialchars($fichier->getId());?>" method="post">
                <label for="nomFichier">Nom du fichier : </label>
                <input type="text" id="nomFichier" name="nomFichier" value="<?= htmlspecialchars($fichier->getNom());?>" required/>
                <br>
                <label for="contenuFichier">Contenu du fichier :</label>
                <textarea id="contenuFichier" name="contenuFichier" rows="15" cols="80"><?= htmlspecialchars($fichier->getContenu()) ?></textarea>
                <br>
                <button type="submit">Enregistrer les modifications</button>
            </form>
        <?php else: ?>
            <p>Erreur : le fichier n'existe pas ou vous n'avez pas les permissions nécessaires.</p>
        <?php endif; ?>
    </section>
    <?php require_once "views/bas.php"; ?>
</div>
