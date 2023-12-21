<?php
$header = __DIR__ . "/partials/header.view.php";
// Titel der Seite eintragen
$title = "Registrieren";
$page = 'register';
include_once($header);
?>
<h1> Willkommen <?php print $param->getForename(); ?></h1>
<?php if($message){
    print "<h3>$message</h3>";}?>

<form action="/profile/update" method="post">
    <div>
        <label for="forename">Vorname</label>
        <input type="text" id="forename" name="forename" value="<?php print $param->getForename() ?>">
    </div>
    <div>
        <label for="surname">Nachname</label>
        <input type="text" id="surname" name="surname" value="<?php print $param->getSurname() ?>">
        <div>
            <label for="email">Email Adresse</label>
            <input type="text" id="email" name="email" value="<?php print $param->getEmail() ?>">
            <div>
                <label for="password">Neues Passwort</label>
                <input type="password" id="password" name="password">
                <button type="submit" class="btn-secondary">Absenden</button>
                <div>
</form>
<button class="btn btn-primary" type="button" onclick="openLink('/profile/history')" style="margin-right: 20px">Buchungsverlauf</button>
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer);
?>
