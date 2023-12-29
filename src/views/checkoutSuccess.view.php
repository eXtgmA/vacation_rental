<?php
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Erfolg";
include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->
<h1>Bezahlung erfolgreich!</h1>
    <a class="btn-secondary btn" href="/dashboard">Plane jetzt schon den nächsten Urlaub</a>
<!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>