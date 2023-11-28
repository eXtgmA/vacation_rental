<?php
$header=__DIR__."\\partials\\header.view.php";
// Titel der Seite eintragen
$title = "";
$page = 'offerindex';
include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->
    <a href="/offer/create">Neues Haus anlegen</a>
<!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."\\partials\\footer.view.php";
include_once($footer)
?>