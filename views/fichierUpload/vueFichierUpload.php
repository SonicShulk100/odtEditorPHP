<div class="container">
    <nav>
        <?php require_once 'views/haut.php'; ?>
    </nav>
    <section>
        <form action="/index.php?action=importer" method="post" enctype="multipart/form-data">
            <label for="fileUpload">Importez votre fichier ODT : </label>
            <input type="file" id="fileUpload" name="fileUpload" accept=".odt" required/>
            <br>
            <input type="hidden" value="<?php echo $_SESSION['csrfToken']; ?>"/>
            <input type="submit" value="Importer" id="uploaded"/>
        </form>
    </section>
    <?php require_once 'views/bas.php'; ?>
</div>
