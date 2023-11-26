<?php
$header=__DIR__."\\partials\\header.view.php";
$title = "Ich bin die Seite";
include_once($header);
?>
    <h1>Willkommen auf der AKAD Ferienhausverwaltung</h1>
    <div style="display: flex; justify-content: center" >
        <div>
            <div>Sie haben schon einen Account ?</div>
            <div><a href="/login">Login</a> </div>
        </div>
        <div>
            <div>Neu auf dieser Seite ?</div>

            <div><a href="/register">Registrieren</a></div>
        </div>
    </div>
<?php
$footer=__DIR__."\\partials\\footer.view.php";
include_once($footer)
?>