<?php
global $message;
$header = __DIR__ . "//partials/header.view.php";
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
        <table>
            <tr>
               <td> <label for="email">Email</label></td>
               <td> <input type="text" name="email"
                       value='<?php echo isset($_SESSION["old_POST"]["email"]) ? $_SESSION["old_POST"]["email"] : ""; ?>'
                       required></td>
            </tr>
            <tr>
              <td>  <label for="password">Password</label></td>
                <td><input type="password" name="password" required></td>
            </tr>
            <tr>
               <td> <button type="submit">Login</button></td>
            </tr>
        </table>

    </form>
</div>
<div style="display: flex;justify-content: center">
    <h3>Noch keinen Account ?
        <div><a href="/register">Account anlegen</a></div>
    </h3>
</div>
<!--Ende HTML Inhalt-->
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>
