<?php

use Random\RandomException;

if (!isset($_SESSION['csrfToken'])){
    try{
        $_SESSION['csrfToken'] = bin2hex(random_bytes(32));
    }
    catch(RandomException $e){
        die($e->getMessage());
    }
}
?>

<div class="container">
    <nav>
        <?php require_once 'views/haut.php'; ?>
    </nav>
    <section>
        <h2>Inscription :</h2>
        <form action="../../index.php?action=inscription" method="post">
            <label for="nom">Nom : </label>
            <br>
            <input type="text" id="nom" name="nom" required/>

            <br>

            <label for="prenom">Prenom : </label>
            <br>
            <input type="text" id="prenom" name="prenom" required/>

            <br>

            <label for="login">Login (Juste, insérez votre mail) : </label>
            <br>
            <input type="text" id="login" name="login" required/>

            <br>

            <label for="password">Mot de passe : </label>
            <br>
            <input type="password" id="password" name="password" required/>

            <br>
            <br>

            <input type="hidden" value="<?php echo $_SESSION['csrfToken'];?>"/>
            <input type="submit" value="Inscrire" class="button"/>
        </form>
        <?php if(isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
    </section>
    <?php require_once 'views/bas.php'; ?>
</div>

<style>
    form{
        border-radius: 5px;
        background: #f2e1d0;
        padding: 20px;
    }

    label{
        font-family: "Consolas", "Courier", "Courier New", monospace;
        font-weight: bold;
        font-size: 150%;
        text-decoration: underline;
    }

    input[type=text], input[type=password]{
        width: 35%;
        padding: 12px 20px;
        margin:8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
</style>