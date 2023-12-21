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
<h1> Willkommen <?php print $user->getForename(); ?></h1>
<?php if (isset($message)) {
    echo "<h3>$message</h3>";
}?>

<form action="/profile/update" method="post">
    <div>
        <label for="forename">Vorname</label>
        <input type="text" id="forename" name="forename" value="<?php print $user->getForename() ?>">
    </div>
    <div>
        <label for="surname">Nachname</label>
        <input type="text" id="surname" name="surname" value="<?php print $user->getSurname() ?>">
        <div>
            <label for="email">Email Adresse</label>
            <input type="text" id="email" name="email" value="<?php print $user->getEmail() ?>">
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
