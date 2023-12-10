<?php
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Registrieren";
$page = 'register';
include_once($header);
?>
<link rel="stylesheet" href="/styles/login.css"/>
<div class="login-card">
    <h1>Neuer Account</h1>
    <h3><?php isset($message)?print $message: Print '' ?></h3>
    <form action="/register" method="post">
        <label id="register-forname-input-label" for="register-forname-input-field">Vorname</label>
        <input id="register-forname-input-field" type="text" name="forename" value='<?php prefill("forename");?>'>
        <label id="register-surname-input-label" for="register-surname-input-field">Nachname</label>
        <input id="register-surname-input-field" type="text" name="surname" value='<?php prefill("surname");?>'>
        <label id="register-password-input-label" for="register-password-input-field">Password</label>
        <input id="register-password-input-field" type="password" name="password" value='<?php prefill("password");?>'>
        <label id="register-password-repeat-input-label" for="register-password-repeat-input-field">Password bestätigen</label>
        <input id="register-password-repeat-input-field" type="password" name="password_confirm" value='<?php prefill("password_confirm");?>'>
        <label id="register-email-input-label" for="register-email-input-field">E-Mail</label>
        <input id="register-email-input-field" type="text" name="email" value='<?php prefill("email");?>'>
        <button class="login-button" type="submit">Registrieren</button>
    </form>
</div>
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer);
?>
