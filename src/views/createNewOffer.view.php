<?php
$header = __DIR__ . "/partials/header.view.php";
// Titel der Seite eintragen
$title = "Haus anlegen";
$page = 'createhouse';
$_SESSION['previous'] = $_SERVER['REQUEST_URI'];
$features = $param['features'] ?? [];
$oldTagsSelected = $_SESSION['old_POST']['tags'] ?? '';

include_once($header);
?>
<link rel="stylesheet" href="/styles/offer-create.css"/>

<form action="/offer/create" method="post" enctype="multipart/form-data" id="create-offer-form">

    <div id="new-offer-area">
        <div class="headline">
            <h1>Neues Haus anlegen</h1>
            <div class="buttons">
                <button class="btn btn-primary" type="button" onclick="openLink('/offer')">Zurück</button>
            </div>
        </div>

        <h2 class="sub-headline">Details</h2>
        <div id="detail-grid">
            <div id="name-area">
                <label class="label" for="name">Name der Anlage*</label>
                <input class="input-field" type="text" name="base-data[name]" id="name" value="<?php echo $_SESSION['old_POST']['base-data']['name'] ?? ""; ?>" required
                       autofocus>
            </div>
            <div id="city-area">
                <label class="label" for="city">Ort*</label>
                <input class="input-field" type="text" name="base-data[city]" id="city" value="<?php echo $_SESSION['old_POST']['base-data']['city'] ?? ""; ?>" required>
            </div>
            <div id="postal-code-area">
                <label class="label" for="postal-code">Postleitzahl*</label>
                <input class="input-field" type="number" name="base-data[postal_code]" id="postal-code" maxlength="5"
                       value="<?php echo $_SESSION['old_POST']['base-data']['postal_code'] ?? ""; ?>" required>
            </div>
            <div id="street-area">
                <label class="label" for="street">Straße*</label>
                <input class="input-field" type="text" name="base-data[street]" id="street" value="<?php echo $_SESSION['old_POST']['base-data']['street'] ?? ""; ?>"
                       required>
            </div>
            <div id="house-number-area">
                <label class="label" for="house-number">Hausnummer*</label>
                <input class="input-field" type="number" name="base-data[house_number]" id="house-number" min="1"
                       value="<?php echo $_SESSION['old_POST']['base-data']['house_number'] ?? ""; ?>" required>
            </div>
            <div id="square-meter-area">
                <label class="label" for="square-meter">Quadratmeter*</label>
                <input class="input-field" type="number" name="base-data[square_meter]" id="square-meter" min="1"
                       value="<?php echo $_SESSION['old_POST']['base-data']['square_meter'] ?? ""; ?>" required>
            </div>
            <div id="room-count-area">
                <label class="label" for="room-count">Anzahl Räume*</label>
                <input class="input-field" type="number" name="base-data[room_count]" id="room-count" min="1"
                       value="<?php echo $_SESSION['old_POST']['base-data']['room_count'] ?? ""; ?>" required>
            </div>
            <div id="max-person-area">
                <label class="label" for="max-person">Anzahl mögliche Personen*</label>
                <input class="input-field" type="number" name="base-data[max_person]" id="max-person" min="1"
                       value="<?php echo $_SESSION['old_POST']['base-data']['max_person'] ?? ""; ?>" required>
            </div>
            <div id="price-area">
                <label class="label" for="price">Preis pro Nacht*</label>
                <input class="input-field" type="number" name="base-data[price]" id="price" min="1"
                       value="<?php echo $_SESSION['old_POST']['base-data']['price'] ?? ""; ?>" required>
            </div>
            <div id="description-area">
                <label class="label" for="description">Beschreibung*</label>
                <div class="description-input">
                    <textarea class="input-field" name="base-data[description]" id="description" cols="30" required
                              rows="10"><?php echo $_SESSION['old_POST']['base-data']['description'] ?? ""; ?></textarea>
                </div>
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
            <?php foreach ($features as $categoryName => $category) { ?>
                <div class="feature-select-list">
                    <div class="feature-topic">
                        <h3 class="sub-headline"><?php echo $categoryName; ?></h3>
                    </div>
                    <?php foreach ($category as $feature) { ?>
                        <label class="feature-select">
                            <input type="checkbox" name="<?php echo 'features[' . $categoryName . '][]" value="' . $feature->getName(); ?>"
                                <?php if (in_array($feature->getName(), ($_SESSION['old_POST']['features'][$categoryName] ?? []))) {
                                    echo ' checked';
                                } ?>>
                            <?php echo $feature->getName(); ?>
                        </label>
                    <?php } ?>
                </div>
            <?php } ?>
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


<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>

<script src="/scripts/offer-create.js"></script>

<script>
    prefillTags("<?php echo $oldTagsSelected ?>");
</script>