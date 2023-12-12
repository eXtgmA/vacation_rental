<?php
global $message;
$header = __DIR__ . "/partials/header.view.php";
$title = "Option erstellen";
$house_id = isset($param) ? $param : null;
include_once($header);
?>
<link rel="stylesheet" href="/styles/option-create.css"/>
<div class="center-content">
    <h1>Neue Option anlegen</h1>
</div>

<div class="option-area center-content" style="margin: 20px">
    <form action="<?php echo "/option/create/" . $house_id ?>" method="post" enctype="multipart/form-data">
        <div class="card ">
            <div id="option-image">
                <div id="drop-area">
                    <p style="margin: 10px">Bild in den makierten Bereich ziehen oder </p>
                    <input type="file" id="option-image-input" accept="image/*" name="optionimage">
                    <label for="option-image-input" id="select-image-label">Bild auswählen</label>
                </div>
            </div>
            <div class="option-name">
                <label class="label" for="option-name-input">Name</label>
                <input class="input-field" type="Text" id="option-name-input" name="name" min="1"
                       value='<?php echo $_SESSION["old_POST"]["name"] ?? ""; ?>' required>
            </div>
            <div class="option-price">
                <label class="label" for="option-price-input">Preis</label>
                <input class="input-field" type="text" id="option-price-input" name="price"
                       value='<?php echo $_SESSION["old_POST"]["price"] ?? ""; ?>' required>
            </div>
            <div class="option-description">
                <label class="label" for="option-description-input">Beschreibung</label>
                <textarea class="input-field" id="option-description-input" style="margin: 0;" name="description"
                          cols="30" rows="10" maxlength="300" required>
                    <?php echo $_SESSION["old_POST"]["description"] ?? ""; ?></textarea>
            </div>
        </div>
        <div class="button-area">
            <button type="button" class="btn-secondary"
                    onclick="openLink('<?php echo "/option/showall/" . $house_id; ?>')">Zurück
            </button>
            <button type="submit" class="btn-primary">Option erstellen</button>
        </div>

    </form>
</div>

<?php
unset($_SESSION["old_POST"]);
// footer
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>
<script src="/scripts/option-create.js"></script>
