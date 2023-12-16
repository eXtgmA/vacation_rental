<?php

use src\models\Option;

$header = __DIR__ . "/partials/header.view.php";
$title = "Alle Optionen";
include_once($header);


if (isset($param["houseId"])) {
    $house_reference = "/offer/show/" . $param["houseId"];
    $houseId = $param["houseId"];
    $_SESSION['previous'] = "/option/showall/{$houseId}";
    unset($param["houseId"]);
} else {
    $house_reference = "/offer";
    $houseId = "#";
}
?>
<link rel="stylesheet" href="/styles/option-overview.css"/>
<div class="headline">
    <h1>Alle angelegten Optionen</h1>
    <?php isset($message)?print ("<h3>".$message."</h3>"):print ''?> <!-- todo : set better position in frontend -->
    <button class="btn-secondary" onclick="openLink('<?php echo $house_reference; ?>')">Zurück</button>
</div>

<div class="options-grid">
    <?php
    /** @var array $param */
    /** @var Option $option */
    if ($param != null) {
        foreach ($param as $option) {
            echo '<div class="card option-card' . (($option->isDisabled() == 1) ? ' disabled' : '') . '">';
            echo '<div class="option-buttons">';
            echo '<button class="edit-button btn-primary">Edit</button>';
            echo "<form action='/option/togglestatus/{$option->getId()}' method='post'><button class='btn-secondary'>"
                . ($option->isDisabled() == 1 ? 'Aktivieren' : 'Deaktivieren')
                ."</button></form>";
            echo "<form action='/option/delete/{$option->getId()}' method='post'><button class='delete-button btn-secondary'>Delete</button></form>";
            echo '</div>';
            echo '<div class="option-image">';
            echo '<img src="/images/' . $option->getOptionImage() . '" alt="alt">';
            echo '</div>';
            echo '<div class="option-name">';
            echo '<h3>' . $option->getName() . '</h3>';
            echo '</div>';
            echo '<div class="option-price">';
            echo '<span class="option-price-label">Preis: </span>';
            echo '<span class="option-price-value">' . $option->getPrice() . '€</span>';
            echo '</div>';
            echo '<div class="option-description">';
            echo '<p>' . $option->getDescription() . '</p>';
            echo '</div>';
            echo '</div>';
        }
    }
    ?>
    <div class="card new-card" id="add-new-option"
         onclick="openLink('/option/create/<?php echo $houseId ?>')">
        <div class="plus-icon">
            <i class="fa-solid fa-plus"></i>
        </div>
    </div>
</div>
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>
