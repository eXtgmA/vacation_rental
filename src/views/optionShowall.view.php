<?php

use src\models\Option;

$header = __DIR__ . "/partials/header.view.php";
$title = "Alle Optionen";
$title = "Option erstellen";
$house_id = $param ?? null;
include_once($header);

if (isset($param["house_id"])) {
    $house_reference = "/offer/show/" . $param["house_id"];
    $house_id = $param["house_id"];
    unset($param["house_id"]);
} else {
    $house_reference = "/offer";
    $house_id = "#";
}
?>
<link rel="stylesheet" href="/styles/option-overview.css"/>
<div class="headline">
    <h1>Alle angelegten Optionen</h1>
    <button class="btn-secondary" onclick="openLink('<?php echo $house_reference; ?>')">Zurück</button>
</div>

<div class="options-grid">
    <?php
    /** @var Option[] $param */
    foreach ($param as $option) {
        echo '<div class="card option-card' . (($option->getIsDisabled() == 1) ? ' disabled' : '') . '">';
        echo '<div class="option-buttons">';
        echo '<button class="edit-button btn-primary">Edit</button>';
        echo '<button class="delete-button btn-secondary">Delete</button>';
        echo '</div>';
        echo '<div class="option-image">';
        echo '<img src="/images/' . $option->getOptionImage() . '" alt="alt">';
        echo '</div>';
        echo '<div class="option-name">';
        echo '<h3>' . $option->getName() . '</h3>';
        echo '</div>';
        echo '<div class="option-price">';
        echo '<span>Preis: </span>';
        echo '<span>' . $option->getPrice() . '</span>';
        echo '</div>';
        echo '<div class="option-description">';
        echo '<p>' . $option->getDescription() . '</p>';
        echo '</div>';
        echo '</div>';
    }
    ?>
    <div class="card new-card" id="add-new-option"
         onclick="openLink('/option/create/<?php echo $house_id ?>')">
        <div class="plus-icon">
            <i class="fa-solid fa-plus"></i>
        </div>
    </div>
</div>
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>
