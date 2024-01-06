<?php
$header = __DIR__ . "/partials/header.view.php";
// Titel der Seite eintragen
$title = "Profil";
$page = 'profile';
$user = $param ?? null;
if ($user == null) {
    new \src\controller\ViewController("notFound");
    die();
}
include_once($header);
?>
<div class="content-layout">
    <div class="headline">
        <h1>Willkommen <?php print $user->getForename(); ?></h1>
        <button class="btn btn-primary" type="button" onclick="openLink('/profile/history')" style="margin-right: 20px">Buchungsverlauf</button>
    </div>
    <link rel="stylesheet" href="/styles/login.css"/>
    <div class="card" style="margin: 5% auto">
        <form action="/profile/update" method="post">
            <label id="register-forname-input-label" class="label" for="register-forname-input-field">Vorname</label>
            <input id="register-forname-input-field" class="input-field" type="text" name="forename" value='<?php print $user->getForename() ?>'>
            <label id="register-surname-input-label" class="label" for="register-surname-input-field">Nachname</label>
            <input id="register-surname-input-field" class="input-field" type="text" name="surname" value="<?php print $user->getSurname() ?>">
            <label id="register-email-input-label" class="label" for="register-email-input-field">E-Mail</label>
            <input id="register-email-input-field" class="input-field" type="text" name="email" value="<?php print $user->getEmail() ?>">
            <br/>
            <label id="register-password-input-label" class="label" for="register-password-input-field">Passwort</label>
            <input id="register-password-input-field" class="input-field" type="password" name="password">
            <button type="submit" class="btn-secondary">Absenden</button>
        </form>
    </div>
</div>

<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer);
?>
