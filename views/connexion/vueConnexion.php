<?php
//partie PHP
use Random\RandomException;

if (!isset($_SESSION['csrfToken'])) {
    try {
        $_SESSION['csrfToken'] = bin2hex(random_bytes(32));
    } catch (RandomException $e) {
        die($e->getMessage());
    }
}
?>

<!-- Partie HTML -->
<div class="container">
    <nav>
        <?php require_once "views/haut.php"; ?>
    </nav>
    <section>
        <h2>Connexion :</h2>
        <form action="../../index.php?action=connecter" method="post">
            <label for="login">Mail : </label>
            <br>
            <input type="text" id="login" name="login" required />
            <br>
            <label for="password">Mot de passe :</label>
            <br>
            <input type="password" id="password" name="password" required />
            <br>
            <br>
            <input type="hidden" name="csrfToken" value="<?php echo $_SESSION['csrfToken']; ?>" />
            <input type="submit" value="Connecter" class="button"/>
        </form>
    </section>
    <?php require_once "views/bas.php"; ?>
</div>

<style>
    input[type=text], input[type=password]{
        width: 35%;
        padding: 12px 20px;
        margin:8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    label{
        font-family: "Consolas", "Courier", "Courier New", monospace;
        font-weight: bold;
        font-size: 150%;
        text-decoration: underline;
        color: aquamarine;
    }

    form{
        border-radius: 5px;
        background-color: #40195b;
        padding: 20px;
    }
</style>