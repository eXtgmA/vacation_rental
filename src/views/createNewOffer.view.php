<?php
$header=__DIR__."\\partials\\header.view.php";
// Titel der Seite eintragen
$title = "Haus anlegen";
$page = 'createhouse';
include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->


    <div style="justify-content: center">
        <div>
            <form action="/offer/create" method="post">

                <table>
                    <tr>
                        <td>Quadratmeter</td>
                        <td><input type="number" name="sqm" min="1"></td>
                    </tr>
                    <tr>
                        <td>Anzahl mögliche Personen</td>
                        <td><input type="number" name="maxpersons" min="1"></td>

                    <tr>
                        <td>Anzahl Räume</td>
                        <td><input type="number" name="rooms" min="1"></td>
                    </tr>
                    <tr>
                        <td>Preis pro Nacht</td>
                        <td><input type="number" name="pricepernight" min="1"></td>
                    </tr>
                    <tr>
                        <td>Ort</td>
                        <td><input type="number" name="zipcode" maxlength="5"></td>
                    </tr>
                    <tr>
                        <td>Straße</td>
                        <td><input type="text" name="streetname"></td>
                    </tr>
                    <tr>
                        <td>Hausnummer</td>
                        <td><input type="number" name="streetnumber" min="1"></td>
                    </tr>

                </table>
                <button type="submit">Haus einstellen</button>
            </form>

        </div>
<!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."\\partials\\footer.view.php";
include_once($footer)
?>