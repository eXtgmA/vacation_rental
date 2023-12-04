<?php
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Haus anlegen";
$page = 'createhouse';
include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->


    <div style="justify-content: center">
        <div>
            <form action="/offer/create" method="post" enctype="multipart/form-data">

                <table>
                    <tr>
                        <td>Name der Anlage</td>
                        <td><input type="Text" name="name" min="1"></td>
                    </tr>
                    <tr>
                        <td>Beschreibung</td>
                        <td><textarea name="description" id="" cols="30" rows="10"></textarea></td>
                    </tr>
                    <tr>
                        <td>Quadratmeter</td>
                        <td><input type="number" name="square_meter" min="1"></td>
                    </tr>
                    <tr>
                        <td>Anzahl mögliche Personen</td>
                        <td><input type="number" name="max_person" min="1"></td>

                    <tr>
                        <td>Anzahl Räume</td>
                        <td><input type="number" name="room_count" min="1"></td>
                    </tr>
                    <tr>
                        <td>Preis pro Nacht</td>
                        <td><input type="number" name="price" min="1"></td>
                    </tr>
                    <tr>
                        <td>Postleitzahl</td>
                        <td><input type="number" name="postal_code" maxlength="5"></td>
                    </tr>
                    <tr>
                        <td>Ort</td>
                        <td><input type="text" name="city"></td>
                    </tr>
                    <tr>
                        <td>Straße</td>
                        <td><input type="text" name="street"></td>
                    </tr>
                    <tr>
                        <td>Hausnummer</td>
                        <td><input type="number" name="house_number" min="1"></td>
                    </tr>
                    <tr>
                        <td> <label for="image" >Frontbild <i class="fa fa-camera"></i></label></td>
                        <td><input type="file" name="image" hidden="hidden" id="image"></td>
                    </tr>
                    </form>
                </table>
                <button type="submit">Haus einstellen</button>
            </form>

        </div>
<!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>