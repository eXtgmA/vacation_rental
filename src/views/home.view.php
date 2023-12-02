<?php
$header=__DIR__."/partials/header.view.php";
$title = "Ich bin die Seite";
$page = 'home';

// make an auto redirect to /dashboard if there is already an active session
if (!isset($_SESSION)) { // avoid double opening sessions
    session_start();
}
if (isset($_SESSION['user'])) {
    header('Location: /dashboard');
    exit();
}

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
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>
