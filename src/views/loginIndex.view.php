<?php
global $message;
$header = __DIR__ . "//partials/header.view.php";
// Titel der Seite eintragen
$title = "login";
$page = "login";
include_once($header);
?>
<link rel="stylesheet" href="/styles/login.css"/>
<div class="login-card">
    <form action="/login" method="post">
        <label id="login-password-input-label" for="login-email-input-field">Email</label>
        <input id="login-email-input-field" type="text" name="email"
                   value='<?php echo isset($_SESSION["old_POST"]["email"]) ? $_SESSION["old_POST"]["email"] : ""; ?>'
                   required>
        <label id="login-password-input-label" for="login-password-input-field">Password</label>
        <input id="login-password-input-field" type="password" name="password" required>
        <button id="login-button" type="submit">Login</button>
        <span id="register-account">Noch keinen Account?<br/><a href="/register">neuen Anlegen</a>
        </span>
    </form>
</div>

<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>
