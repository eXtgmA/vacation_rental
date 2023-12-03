<?php
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Datailseite für ein hausobject";
$house = isset($param) ? $param : null;
include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->
<h1>Details für Haus <?php echo $house->getName();?> <a href="/offer"><p class="fa fa-chevron-left"></p> zurück</a></h1>
<!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>