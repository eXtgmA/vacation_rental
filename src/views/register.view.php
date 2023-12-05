<?php
global $message;
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Registrieren";
$page = 'register';
include_once($header);
?>
<h1>Registriere einen neuen Account</h1>
<h3><?php echo $message; ?></h3>
    <!--Hier den HTML Inhalt einfuegen-->
<div style="justify-content: center">
    <div>
        <form action="/register" method="post">

        <table>
            <tr>
                <td>Vorname</td>
                <td><input type="text" name="forename" value='<?php echo isset($_SESSION["old_POST"]["forename"]) ? $_SESSION["old_POST"]["forename"] : "";?>'></td>
            </tr>
            <tr>
                <td>Nachname</td>
                <td><input type="text" name="surname" value='<?php echo isset($_SESSION["old_POST"]["surname"]) ? $_SESSION["old_POST"]["surname"] : "";?>'></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password" value='<?php echo isset($_SESSION["old_POST"]["password"]) ? $_SESSION["old_POST"]["password"] : "";?>'></td>
            </tr>
            <tr>
                <td>Password bestätigen</td>
                <td><input type="password" name="password_confirm" value='<?php echo isset($_SESSION["old_POST"]["password_confirm"]) ? $_SESSION["old_POST"]["password_confirm"] : "";?>'></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text" name="email" value='<?php echo isset($_SESSION["old_POST"]["email"]) ? $_SESSION["old_POST"]["email"] : "";?>'></td>
            </tr>
        </table>
            <button>Registrieren</button>
        </form>

    </div>

</div>
    <!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer);
?>
