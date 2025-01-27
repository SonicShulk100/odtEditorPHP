<?php
if (!isset($_SESSION['csrfToken'])) {
    try {
        $_SESSION['csrfToken'] = bin2hex(random_bytes(32));
    } catch (\Random\RandomException $e) {

    }
}
?>
<form action="/index.php?action=connecter" method="post">
    <label for="login">Nom d'utilisateur : </label>
    <input type="text" id="login" name="login" required />
    <br>
    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required />
    <br>
    <input type="hidden" name="csrfToken" value="<?php echo $_SESSION['csrfToken']; ?>" />
    <input type="submit" value="Connecter" />
</form>

