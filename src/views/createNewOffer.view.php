<?php
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Haus anlegen";
$page = 'createhouse';
$_SESSION['previous'] = $_SERVER['REQUEST_URI'];
function pold($string){
    $old = $_SESSION['old_POST'] ?? null;
    if(isset($old)){
        print $old[$string]??'';
    }
}
include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->


    <div style="justify-content: center">
        <div>
            <h3><?php isset($message)?print ($message):print ''?></h3>
            <form action="/offer/create" method="post" enctype="multipart/form-data">

                <table>
                    <tr>
                        <td>Name der Anlage</td>
                        <td><input type="Text" name="name" value="<?php pold('name') ?>"></td>
                    </tr>
                    <tr>
                        <td>Beschreibung</td>
                        <td><textarea name="description" id="" cols="30" rows="10"><?php pold('description') ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Quadratmeter</td>
                        <td><input type="number" name="square_meter" min="1" value="<?php pold('square_meter') ?>"></td>
                    </tr>
                    <tr>
                        <td>Anzahl mögliche Personen</td>
                        <td><input type="number" name="max_person" min="1" value="<?php pold('max_person') ?>"></td>

                    <tr>
                        <td>Anzahl Räume</td>
                        <td><input type="number" name="room_count" min="1" value="<?php pold('room_count') ?>"></td>
                    </tr>
                    <tr>
                        <td>Preis pro Nacht</td>
                        <td><input type="number" name="price" min="1" value="<?php pold('price') ?>"></td>
                    </tr>
                    <tr>
                        <td>Postleitzahl</td>
                        <td><input type="number" name="postal_code" maxlength="5" value="<?php pold('postal_code') ?>"></td>
                    </tr>
                    <tr>
                        <td>Ort</td>
                        <td><input type="text" name="city" value="<?php pold('city') ?>"></td>
                    </tr>
                    <tr>
                        <td>Straße</td>
                        <td><input type="text" name="street" value="<?php pold('street') ?>"></td>
                    </tr>
                    <tr>
                        <td>Hausnummer</td>
                        <td><input type="number" name="house_number" min="1" value="<?php pold('house_number') ?>"></td>
                    </tr>
                    <tr>
                        <td> <label for="frontimage" >Frontbild <i class="fa fa-camera"></i></label></td>
                        <td><input type="file" name="frontimage" hidden="hidden" id="frontimage"></td>
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