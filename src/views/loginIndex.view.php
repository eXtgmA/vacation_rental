<?php
$header = __DIR__ . "//partials/header.view.php";
// Titel der Seite eintragen
$title = "login";
$page = "login";
$_SESSION['previous']='/login';
include_once($header);
?>
<link rel="stylesheet" href="/styles/login.css"/>
<div class="card" style="margin: 10% auto">
    <h1>Anmelden</h1>

    <form action="/login" method="post">
        <label id="login-password-input-label" class="label" for="login-email-input-field">Email</label>
        <input id="login-email-input-field" class="input-field" type="text" name="email"
                   value='<?php echo isset($_SESSION["old_POST"]["email"]) ? $_SESSION["old_POST"]["email"] : ""; ?>'
                   required>
        <label id="login-password-input-label" class="label" for="login-password-input-field">Passwort</label>
        <input id="login-password-input-field" class="input-field" type="password" name="password" required>
        <button class="btn-secondary" type="submit">Login</button>
        <span id="register-account">Noch keinen Account?<br/><a href="/register">neuen Anlegen</a>
        </span>
    </form>
</div>

<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>
