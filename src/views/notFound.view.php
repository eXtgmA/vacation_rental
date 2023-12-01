<?php
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Seite nicht gefunden";
include_once($header);
?>
    <!--Hier den HTML Inhalt einfuegen-->
    <div style="display: flex;justify-content: center">
        <h3>Entschuldigung, die gewünschte Seite konnte nicht gefunden werden</h3>
    </div>
    <!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>