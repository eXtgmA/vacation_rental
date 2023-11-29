<?php
global $message;
$header = __DIR__ . "\\partials\\header.view.php";
// Titel der Seite eintragen
$title = "login";
$page = "login";
include_once($header);
?>
    <!--Hier den HTML Inhalt einfuegen-->
    <h1>Login Page</h1>
    <div style="display: flex;justify-content: center">
        <h3>Login</h3>
        <?php
        echo($message ?? "<h1>$message</h1>");
        ?>

    </div>
    <div style="display: flex;justify-content: center">
        <form action="/login" method="post">
            <label for="username">Username</label>
            <input type="text" name="username" required>
            <br>
            <br>
            <label for="password">Password</label>
            <input type="password" name="password" required>
            <br>
            <br>
            <button type="submit">Login</button>
        </form>
    </div>
    <div style="display: flex;justify-content: center">
        <h3>Noch keinen Account ?
            <div><a href="/register">Account anlegen</a></div>
        </h3>
        <h3>Passwort Vergessen ?
            <div><a href="/register">Reset</a></div>
        </h3>
    </div>
    <!--Ende HTML Inhalt-->
<?php
$footer = __DIR__ . "\\partials\\footer.view.php";
include_once($footer)
?>