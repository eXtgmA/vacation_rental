<?php
$header = __DIR__ . "/partials/header.view.php";
// Titel der Seite eintragen
$title = "Datailseite für ein hausobject";
$house = isset($param) ? $param : null;
include_once($header);
?>

<?php // sort photos
/** @var \src\models\Image[]|array<\src\models\Image[]> $images */
$images = [];
$result = $house->getImages();
if ($result) {
    foreach ($result as $img) {
        $tId = $img->getTypetableId();
        if ($tId == 1) {
            $images['front'] = $img;
        } elseif ($tId == 2) {
            $images['layout'] = $img;
        } elseif ($tId == 4) {
            $images['optional'][] = $img;
        }
    }
}
?>

<link rel="stylesheet" href="/styles/offer-create.css"/>
<form action="/offer/edit/<?php echo $house->getId() ?>" method="post" enctype="multipart/form-data" id="edit-offer-form">
    <div id="new-offer-area">
        <div class="headline">
            <h1>Bearbeiten des Ferienhauses <span style="font-style: italic"><?php echo $house->getName() ?></span></h1>
            <div>
                <button class="btn btn-secondary" type="button" onclick="openLink('/offer')" style="margin-right: 20px">Zurück</button>
                <button class="btn btn-primary" type="button" onclick="openLink('<?php echo "/option/showall/" . $house->getId(); ?>')">Optionen bearbeiten</button>
            </div>
        </div>

        <h2 class="sub-headline">Details</h2>
        <div id="detail-grid">
            <div id="name-area">
                <label class="label" for="name">Name der Anlage*</label>
                <input class="input-field" type="text" name="name" id="name" value="<?php echo $house->getName(); ?>" required autofocus>
            </div>
            <div id="city-area">
                <label class="label" for="city">Ort*</label>
                <input class="input-field" type="text" name="city" id="city" value="<?php echo $house->getCity(); ?>" required>
            </div>
            <div id="postal-code-area">
                <label class="label" for="postal-code">Postleitzahl*</label>
                <input class="input-field" type="number" name="postal-code" id="postal-code" maxlength="5" value="<?php echo $house->getPostalCode(); ?>" required>
            </div>
            <div id="street-area">
                <label class="label" for="street">Straße*</label>
                <input class="input-field" type="text" name="street" id="street"
                       value="<?php echo $house->getStreet(); ?>" required>
            </div>
            <div id="house-number-area">
                <label class="label" for="house-number">Hausnummer*</label>
                <input class="input-field" type="number" name="house-number" id="house-number" min="1" value="<?php echo $house->getHouseNumber(); ?>" required>
            </div>
            <div id="square-meter-area">
                <label class="label" for="square_meter">Quadratmeter*</label>
                <input class="input-field" type="number" name="square-meter" id="square_meter" min="1" value="<?php echo $house->getSquareMeter(); ?>" required>
            </div>
            <div id="room-count-area">
                <label class="label" for="room-count">Anzahl Räume*</label>
                <input class="input-field" type="number" name="room-count" id="room-count" min="1" value="<?php echo $house->getRoomCount(); ?>" required>
            </div>
            <div id="max-person-area">
                <label class="label" for="max_person">Anzahl mögliche Personen*</label>
                <input class="input-field" type="number" name="max-person" id="max_person" min="1" value="<?php echo $house->getMaxPerson(); ?>" required>
            </div>
            <div id="price-area">
                <label class="label" for="price">Preis pro Nacht*</label>
                <input class="input-field" type="number" name="price" id="price" min="1" value="<?php echo $house->getPrice(); ?>" required>
            </div>
            <div id="description-area">
                <label class="label" for="description">Beschreibung*</label>
                <textarea class="input-field" name="description" id="description" cols="30" required rows="10"><?php echo $house->getDescription(); ?></textarea>
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
                        <input type="file" class="image-upload-input" id="front-image-input-field" accept="image/*" name="front-image-input">
                        <label class="image-upload-label" for="front-image-input-field" id="front-image-label" tabindex="0">Bild auswählen</label>
                    </div>
                </div>
            </div>
            <div id="layout-image-area">
                <h4 style="margin-top: inherit;">Grundriss</h4>
                <div id="layout-image" class="image-upload-area image-container">
                    <div id="layout-image-drop-area" class="image-upload-drop-area">
                        <p class="image-upload-hint">Bild in den markierten Bereich ziehen oder </p>
                        <input type="file" class="image-upload-input" id="layout-image-input-field" accept="image/*" name="layout-image-input">
                        <label class="image-upload-label" for="layout-image-input-field" id="layout-image-label" tabindex="0">Bild auswählen</label>
                    </div>
                </div>
            </div>
        </div>
        <h3 class="sub-headline" style="margin-top: 25px">Optionale Bilder</h3>
        <div id="optional-images-grid">
            <div id="optional-image-drop-area" class="image-upload-drop-area">
                <p class="image-upload-hint">Bild in den markierten Bereich ziehen oder </p>
                <input type="file" class="image-upload-input" id="optional-image-input-field" accept="image/*" multiple name="optional-images[]">
                <label class="image-upload-label" for="optional-image-input-field" id="optional-image-label" tabindex="0">Bild auswählen</label>
            </div>
        </div>

        <h2 class="sub-headline">Ausstattung</h2>
        <!-- TODO: Ask Björn for best structure to post the feature list -->
        <div id="feature-grid">
            <div class="feature-select-list">
                <div class="feature-topic">
                    <h3 class="sub-headline">Outdoor</h3>
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
                    <h3 class="sub-headline">Wellness</h3>
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
                    <h3 class="sub-headline">Bad</h3>
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
                    <h3 class="sub-headline">Multimedia</h3>
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
                    <h3 class="sub-headline">Küche</h3>
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
                    <h3 class="sub-headline">Sonstiges</h3>
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
            <button class="btn btn-secondary" type="button" onclick="openLink('/offer')" style="margin-right: 20px">Zurück</button>
            <button class="btn-primary" type="submit">Speichern</button>
        </div>
    </div>
</form>


<div>
    <a href="/offer"><p class="fa fa-chevron-left"></p> zurück</a>
</div>
<h1>Bearbeiten von Details für Haus <?php echo $house->getName(); ?></h1>
<table>
    <tr>
        <form action="/offer/edit/<?php echo $house->getId() ?>" method="POST">

            <td>Name der Anlage</td>
            <td><p><input type="text" name="name" value="<?php echo $house->getName(); ?>"></p></td>
            <button type="submit">senden</button>
        </form>
    </tr>
    <tr>
        <td>Beschreibung</td>
        <td><p><?php echo $house->getDescription(); ?></p></td>
    </tr>
    <tr>
        <td>Quadratmeter</td>
        <td><p><?php echo $house->getSquareMeter(); ?></p></td>
    </tr>
    <tr>
        <td>Anzahl mögliche Personen</td>
        <td><p><?php echo $house->getMaxPerson(); ?></p></td>

    <tr>
        <td>Anzahl Räume</td>
        <td><p><?php echo $house->getRoomCount(); ?></p></td>
    </tr>
    <tr>
        <td>Preis pro Nacht</td>
        <td><p><?php echo $house->getPrice(); ?></p></td>
    </tr>
    <tr>
        <td>Postleitzahl</td>
        <td><p><?php echo $house->getPostalCode(); ?></p></td>
    </tr>
    <tr>
        <td>Ort</td>
        <td><p><?php echo $house->getCity(); ?></p></td>
    </tr>
    <tr>
        <td>Straße</td>
        <td><p><?php echo $house->getStreet(); ?></p></td>
    </tr>
    <tr>
        <td>Hausnummer</td>
        <td><p><?php echo $house->getHouseNumber(); ?></p></td>
    </tr>
    <?php // sort photos
    /** @var \src\models\Image[]|array<\src\models\Image[]> $images */
    $images = [];
    $result = $house->getImages();
    if ($result != false) {
        foreach ($result as $img) {
            $tId = $img->getTypetableId();
            if ($tId == 1) {
                $images['front'] = $img;
            } elseif ($tId == 2) {
                $images['layout'] = $img;
            } elseif ($tId == 4) {
                $images['optional'][] = $img;
            }
        }
    }
    ?>
    <tr>
        <td>Frontansicht</td>
        <td>
            <img src="<?php if (isset($images['front'])) {
                echo "/images/".$images['front']->getUuid();
                      }?>" style="width: 100px;height: 100px" alt="alt">
        </td>
    </tr>
    <tr>
        <td>Grundriss</td>
        <td>
            <img src="<?php if (isset($images['layout'])) {
                echo "/images/".$images['layout']->getUuid();
                      }?>" style="width: 100px;height: 100px" alt="alt">
        </td>
    </tr>
    <tr>
        <td>Zusatzfotos</td>
        <?php if (isset($images['optional'])) {
            foreach ($images['optional'] as $image) { ?>
                <td>
                    <img src="<?php echo "/images/".$image->getUuid();?>" style="width: 100px;height: 100px" alt="alt">
                </td>
            <?php }
        } ?>
    </tr>
    <tr>
        <td>Status</td>
        <td><p><?php echo $house->getIsDisabled(); ?></p></td>
    </tr>
</table>
<div>
    <a href="<?php echo "/option/showall/" . $house->getId(); ?>"><h1>Manage Options</h1></a>
</div>
<!--Ende HTML Inhalt-->
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>
<script src="/scripts/offer-create.js"></script>

<script>
    // preload the frontImage
    <?php if (isset($images['front'])) { ?>
    fetch("<?php echo "/images/" . $images['front']->getUuid() ?>")
        .then(response => response.blob())
        .then(blob => addPrimaryImage(new File([blob], "<?php echo $house->getFrontimage() ?>", {type: "image"}), frontImageContainer, frontImageDropArea, frontImageSelectElement))
        .catch(error => console.error(error));
    <?php } ?>

    // preload the layoutImage
    <?php if (isset($images['layout'])) { ?>
    fetch("<?php echo "/images/" . $images['layout']->getUuid() ?>")
        .then(response => response.blob())  // convert to blob
        .then(blob => addPrimaryImage(new File([blob], "<?php echo $house->getLayoutImage() ?>", {type: "image"}), layoutImageContainer, layoutImageDropArea, layoutImageSelectElement))
        .catch(error => console.error(error));
    <?php } ?>

    // preload the optional images
    // Fetch all optional images associated with the house
    <?php if (isset($images['optional'])) { ?>
    // Create an array to store the File temporary
    let tempFiles = [];
    Promise.all(<?php echo json_encode(array_map(function ($image) {
        return $image->getUuid();
    }, $images['optional'])) ?>?.map(image =>
        fetch("/images/" + image)
            .then(response => response.blob())
            .then(blob => tempFiles.push(new File([blob], image, {type: "image"})))
            .catch(error => console.error(error))
    )
    ).then(() => addOptionalImage(tempFiles))
        .catch(error => console.error(error));
    <?php } ?>

    // preload the tags
    (<?php echo json_encode($house->getTags()); ?>)?.forEach(tag => {
        addTag(tag);
    });
</script>
