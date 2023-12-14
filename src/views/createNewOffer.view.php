<?php
$header = __DIR__ . "/partials/header.view.php";
// Titel der Seite eintragen
$title = "Haus anlegen";
$page = 'createhouse';
$_SESSION['previous'] = $_SERVER['REQUEST_URI'];
/**
 * @param string $string
 * @return void
 */
function pold($string): void
{
    $old = $_SESSION['old_POST'] ?? null;
    if (isset($old)) {
        print $old[$string] ?? '';
    }
}

include_once($header);
?>
<link rel="stylesheet" href="/styles/offer-create.css"/>
<form action="/offer/create" method="post" enctype="multipart/form-data" id="create-offer-form">

    <div id="new-offer-area">
        <div class="headline">
            <h1>Neues Haus anlegen</h1>
            <button class="btn btn-primary" onclick="">Zurück</button>
            <!--            <button class="btn btn-primary">Optionen bearbeiten</button>-->
        </div>

        <h2 class="sub-headline">Details</h2>
        <div id="detail-grid">
            <div id="name-area">
                <label class="label" for="name">Name der Anlage*</label>
                <input class="input-field" type="text" name="name" id="name" value="<?php pold('name') ?>" required
                       autofocus>
            </div>
            <div id="city-area">
                <label class="label" for="city">Ort*</label>
                <input class="input-field" type="text" name="city" id="city" value="<?php pold('city') ?>" required>
            </div>
            <div id="postal-code-area">
                <label class="label" for="postal-code">Postleitzahl*</label>
                <input class="input-field" type="number" name="postal-code" id="postal-code" maxlength="5"
                       value="<?php pold('postal_code') ?>" required>
            </div>
            <div id="street-area">
                <label class="label" for="street">Straße*</label>
                <input class="input-field" type="text" name="street" id="street" value="<?php pold('street') ?>"
                       required>
            </div>
            <div id="house-number-area">
                <label class="label" for="house-number">Hausnummer*</label>
                <input class="input-field" type="number" name="house-number" id="house-number" min="1"
                       value="<?php pold('house_number') ?>" required>
            </div>
            <div id="square-meter-area">
                <label class="label" for="square_meter">Quadratmeter*</label>
                <input class="input-field" type="number" name="square-meter" id="square_meter" min="1"
                       value="<?php pold('square_meter') ?>" required>
            </div>
            <div id="room-count-area">
                <label class="label" for="room-count">Anzahl Räume*</label>
                <input class="input-field" type="number" name="room-count" id="room-count" min="1"
                       value="<?php pold('room_count') ?>" required>
            </div>
            <div id="max-person-area">
                <label class="label" for="max_person">Anzahl mögliche Personen*</label>
                <input class="input-field" type="number" name="max-person" id="max_person" min="1"
                       value="<?php pold('max_person') ?>" required>
            </div>
            <div id="price-area">
                <label class="label" for="price">Preis pro Nacht*</label>
                <input class="input-field" type="number" name="price" id="price" min="1"
                       value="<?php pold('price') ?>" required>
            </div>
            <div id="description-area">
                <label class="label" for="description">Beschreibung*</label>
                <textarea class="input-field" name="description" id="description" cols="30" required
                          rows="10"><?php pold('description') ?></textarea>
            </div>
        </div>

        <h2 class="sub-headline">Bilder</h2>
        <h3 class="sub-headline">Benötigte Bilder</h3>
        <div id="required-images-grid">
            <div id="front-image-area">
                <h4 style="margin-top: inherit;">Frontansicht</h4>
                <div id="front-image" class="image-upload-area image-container">
                    <div id="front-image-drop-area" class="image-upload-drop-area">
                        <p class="image-upload-hint">Bild in den markierten Bereich ziehen oder </p>
                        <input type="file" class="image-upload-input" id="front-image-input-field"
                               accept="image/*" name="front-image-input">
                        <label class="image-upload-label" for="front-image-input-field"
                               id="front-image-label" tabindex="0">Bild auswählen</label>
                    </div>
                </div>
            </div>
            <div id="layout-image-area">
                <h4 style="margin-top: inherit;">Grundriss</h4>
                <div id="layout-image" class="image-upload-area image-container">
                    <div id="layout-image-drop-area" class="image-upload-drop-area">
                        <p class="image-upload-hint">Bild in den markierten Bereich ziehen oder </p>
                        <input type="file" class="image-upload-input" id="layout-image-input-field"
                               accept="image/*" name="layout-image-input">
                        <label class="image-upload-label" for="layout-image-input-field"
                               id="layout-image-label" tabindex="0">Bild auswählen</label>
                    </div>
                </div>
            </div>
        </div>
        <h3 class="sub-headline" style="margin-top: 25px">Optionale Bilder</h3>
        <div id="optional-images-grid">
            <div id="optional-image-drop-area" class="image-upload-drop-area">
                <p class="image-upload-hint">Bild in den markierten Bereich ziehen oder </p>
                <input type="file" class="image-upload-input" id="optional-image-input-field"
                       accept="image/*" multiple name="optional-images[]">
                <label class="image-upload-label" for="optional-image-input-field"
                       id="optional-image-label" tabindex="0">Bild auswählen</label>
            </div>
        </div>

        <h2 class="sub-headline">Ausstattung</h2>
        <div id="feature-grid">
            <div class="feature-select-list">
                <div class="feature-topic">
                    <h3>Outdoor</h3>
                </div>
                <label class="feature-select">
                    <input type="checkbox">
                    Pool
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Garten
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Terrasse
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Balkon
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Grill
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Parkplatz
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Garage
                </label>
            </div>
            <div class="feature-select-list">
                <div class="feature-topic">
                    <h3>Wellness</h3>
                </div>
                <label class="feature-select">
                    <input type="checkbox">
                    Sauna
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Whirlpool
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Fitnessraum
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Solarium
                </label>
            </div>
            <div class="feature-select-list">
                <div class="feature-topic">
                    <h3>Bad</h3>
                </div>
                <label class="feature-select">
                    <input type="checkbox">
                    Badewanne
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Dusche
                </label>
            </div>
            <div class="feature-select-list">
                <div class="feature-topic">
                    <h3>Multimedia</h3>
                </div>
                <label class="feature-select">
                    <input type="checkbox">
                    TV
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Radio
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Internet
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Telefon
                </label>
            </div>
            <div class="feature-select-list">
                <div class="feature-topic">
                    <h3>Küche</h3>
                </div>
                <label class="feature-select">
                    <input type="checkbox">
                    Kühlschrank
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Gefrierschrank
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Backofen
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Mikrowelle
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Geschirrspüler
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Kaffeemaschine
                </label>
            </div>
            <div class="feature-select-list">
                <div class="feature-topic">
                    <h3>Sonstiges</h3>
                </div>
                <label class="feature-select">
                    <input type="checkbox">
                    Klimaanlage
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Heizung
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Waschmaschine
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Trockner
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Bügeleisen
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Bügelbrett
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Kinderbett
                </label>
                <label class="feature-select">
                    <input type="checkbox">
                    Hochstuhl
                </label>
            </div>
        </div>


        <h2 class="sub-headline">Tags</h2>
        <div id="tag-grid">
            <label>
                <span class="label" style="margin-top: auto">Tag hinzufügen</span>
                <input class="input-field" type="text" id="tag-input-field">
            </label>
            <button class="btn btn-primary" disabled id="add-tag-button">Tag hinzufügen</button>
        </div>

        <div class="footline">
            <button class="btn-primary" type="submit">Haus einstellen</button>
        </div>


    </div>
</form>


<!--Hier den HTML Inhalt einfuegen-->


<div style="justify-content: center">
    <div>
        <h3><?php isset($message) ? print ($message) : print '' ?></h3>
        <form action="/offer/create" method="post" enctype="multipart/form-data">

            <table>
                <tr>
                    <td>Name der Anlage</td>
                    <td><input type="Text" name="name" value="<?php pold('name') ?>"></td>
                </tr>
                <tr>
                    <td>Beschreibung</td>
                    <td><textarea name="description" id="" cols="30" rows="10"><?php pold('description') ?></textarea>
                    </td>
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
                    <td><label for="frontimage">Frontbild <i class="fa fa-camera"></i></label></td>
                    <td><input type="file" name="frontimage" hidden="hidden" id="frontimage"></td>
                </tr>
            </table>
            <button type="submit">Haus einstellen</button>
        </form>

    </div>
</div>
<!--Ende HTML Inhalt-->
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>

<script src="/scripts/offer-create.js"></script>
