<?php
global $message;
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "optioncreation";
include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->
    <h1>Create Option</h1>
        <?php
        echo($message ?? "<h1>$message</h1>");
        ?>

    <div style="justify-content: center">
        <div>
            <form action="/option/create" method="post">

                <table>
                    <tr>
                        <td>Name der Option</td>
                        <td><input type="Text" name="name" min="1" value='<?php echo isset($_SESSION["old_POST"]["name"]) ? $_SESSION["old_POST"]["name"] : "";?>' required></td>
                    </tr>
                    <tr>
                        <td>Beschreibung</td>
                        <td><textarea name="description" cols="30" rows="10" required> <?php echo isset($_SESSION["old_POST"]["description"]) ? $_SESSION["old_POST"]["description"] : "";?> </textarea></td>
                    </tr>
                    <tr>
                        <td>Preis</td>
                        <td><input type="number" name="price" min="1" value='<?php echo isset($_SESSION["old_POST"]["price"]) ? $_SESSION["old_POST"]["price"] : "";?>' required></td>
                    </tr>
                    <tr>
                        <td>Fotos</td>
                        <td>Hier werden Fotos hochgeladen</td>
                    </tr>

                </table>
                <button type="submit">Option erstellen</button>
            </form>

        </div>
<!--Ende HTML Inhalt-->
<?php
unset($_SESSION["old_POST"]);
// footer
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>