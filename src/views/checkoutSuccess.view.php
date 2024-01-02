<?php
$header = __DIR__ . "/partials/header.view.php";
// Titel der Seite eintragen
$title = "Erfolg";
include_once($header);
?>
    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center">
        <h1>Bezahlung erfolgreich!</h1>
        <br/>
        <button class="btn-secondary" onclick="openLink('/dashboard')">Plane jetzt schon den nächsten Urlaub</button>
    </div>
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>