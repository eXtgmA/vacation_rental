<?php
$header=__DIR__."\\partials\\header.view.php";
// Titel der Seite eintragen
$title = "Registrieren";
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
                <td>Username</td>
                <td><input type="text" name="username"></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password"></td>
            </tr>
            <tr>
                <td>Password bestätigen</td>
                <td><input type="password" name="password_confirm"></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text" name="email"></td>
            </tr>
        </table>
            <button>Registrieren</button>
        </form>

    </div>

</div>



    <!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."\\partials\\footer.view.php";
include_once($footer)
?>