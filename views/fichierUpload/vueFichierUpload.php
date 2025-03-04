<div class="container">
    <nav>
        <?php require_once 'views/haut.php'; ?>
    </nav>
    <section>
        <h2>Importation du fichier ODT :</h2>
        <!-- Formulaire pour l'importation du fichier ODT (OpenDocument) -->
        <form action="/index.php?action=importer" method="post" enctype="multipart/form-data">
            <label for="fileUpload">Importez votre fichier ODT : </label>
            <br>
            <input type="file" id="fileUpload" name="fileUpload" accept=".odt" required/>
            <br>
            <input type="hidden" value="<?php echo $_SESSION['csrfToken']; ?>"/>
            <input type="submit" class="button" value="Importer" id="uploaded"/>
        </form>
    </section>
    <?php require_once 'views/bas.php'; ?>
</div>

<!-- Styles -->
<style>
    form{
        border-radius: 5px;
        background-color: #40195b;
        padding: 20px;
    }
    label{
        font-family: "Consolas", "Courier", "Courier New", monospace;
        font-weight: bold;
        font-size: 150%;
        text-decoration: underline;
        color: aquamarine;
    }
    input[type="file"]{
        margin: 12px 20px;
        width: 35%;
        padding: 12px 20px;
        border: 4px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        color: white;
    }
</style>