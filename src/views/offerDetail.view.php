<?php
$header = __DIR__ . "/partials/header.view.php";
// Titel der Seite eintragen
$title = "Datailseite für ein hausobject";
$house = isset($param) ? $param : null;

$images = $house->getImages();
$mainImage = $images[0];

include_once($header);
?>

<link rel="stylesheet" href="/styles/offer-detail.css"/>
<script src="/scripts/calendar-widget.js"></script>

<div id="detail-grid">
    <div id="image-area">
        <div class="carousel">
            <div id="preview-container" class="preview" style="background-image: url('<?php echo "/images/" . $mainImage->getUuid(); ?>')">
                <img id="preview" src="<?php echo "/images/" . $mainImage->getUuid(); ?>" alt="Main Image"/>
            </div>
            <div id="thumbnails">
                <?php foreach (array_filter($images, function ($image) {
                    return $image->getTypetableId() != 3;
                }) as $image): ?>
                    <div class="thumbnail"
                         onclick="document.getElementById('preview').src = this.querySelector('img').src; document.getElementById('preview-container').style.backgroundImage = 'url(' + this.querySelector('img').src + ')';">
                        <img src="<?php echo "/images/" . $image->getUuid(); ?>">
                        <span class="overlay"><i class="fa-solid fa-magnifying-glass"></i></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div id="calendar-area">
        <h2>Buchungsübersicht</h2>
        <div class="calendar-container">
            <div id="calendar-detail" class="calendar"></div>
        </div>
        <script>
            drawCalendar("calendar-detail", currentMonth, currentYear, bookedDays);
        </script>
    </div>

    <div id="information-area">
        <div id="information-grid">
            <div id="price-area" class="information">
                <span class="information-key">Preis pro Nacht:</span>
                <span class="information-value"><?php echo $house->getPrice(); ?>€</span>
                <hr/>
            </div>
            <div id="city-area" class="information">
                <span class="information-key">Ort:</span>
                <span class="information-value"><?php echo $house->getPostalCode() . ' ' . $house->getCity() ?></span>
                <hr/>
            </div>
            <div id="street-area" class="information">
                <span class="information-key">Straße:</span>
                <span class="information-value"><?php echo $house->getStreet() . ' ' . $house->getHouseNumber() ?></span>
                <hr/>
            </div>
            <div id="person-area" class="information">
                <span class="information-key">Max. Personen<span class="additional-text">anzahl:</span></span>
                <span class="information-value"><?php echo $house->getMaxPerson() ?></span>
                <hr/>
            </div>
            <div id="space-area" class="information">
                <span class="information-key">Wohnfläche:</span>
                <span class="information-value"><?php echo $house->getSquareMeter() ?>m²</span>
                <hr/>
            </div>
            <div id="room-area" class="information">
                <span class="information-key">Raumanzahl:</span>
                <span class="information-value"><?php echo $house->getRoomCount() ?></span>
                <hr/>
            </div>
            <div id="submit-area">
                <button class="btn-primary">Buchen</button>
            </div>
        </div>
    </div>

    <div id="description-area">
        <div class="headline">
            <h1><?php echo $house->getName() ?></h1>
        </div>
        <div class="card">
            <h2>Beschreibung</h2>
            <p><?php echo $house->getDescription(); ?></p>
            <br/>
            <h2>Ausstattung</h2>
            <?php $categories = [];
            foreach ($house->getAllFeatures() as $feature) {
                $categories[] = $feature->getCategory();
            }
            $categories = array_unique($categories);
            foreach ($categories as $category) { ?>
                <div class="feature-select-list">
                    <div class="feature-topic">
                        <h3 class="sub-headline"><?php echo $category ?></h3>
                    </div>
                    <div class="feature-list">
                        <?php foreach ($house->getAllFeatures() as $feature) { ?>
                            <?php if ($feature->getCategory() == $category) { ?>
                                <!--                                <span><i class="fa-solid fa-check"></i> --><?php //echo $feature->getName() ?><!--</span>-->
                                <label class="feature-select">
                                    <input type="checkbox" checked disabled>
                                    <?php echo $feature->getName() ?>
                                </label>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div id="options-area">
        <div class="options">
            <div class="options-grid">
                <?php
                /** @var array $param */
                /** @var Option $option */
                if ($param != null) {
                    foreach ($param->getAllOptions() as $option) {
                        echo '<div class="card option-card' . (($option->isDisabled() == 1) ? ' disabled' : '') . '">';
                        echo '  <div class="option-image">';
                        echo '    <img src="/images/' . $option->getOptionImage() . '" alt="alt">';
                        echo '  </div>';
                        echo '    <div class="option-name">';
                        echo '    <h3>' . $option->getName() . '</h3>';
                        echo '  </div>';
                        echo '  <div class="option-price">';
                        echo '    <span class="option-price-label">Preis: </span>';
                        echo '    <span class="option-price-value">' . $option->getPrice() . '€</span>';
                        echo '  </div>';
                        echo '  <div class="option-description">';
                        echo '    <p>' . $option->getDescription() . '</p>';
                        echo '  </div>';
                        echo '</div>';
                    }
                }
                ?>

            </div>
        </div>
    </div>


</div>


<!--Hier den HTML Inhalt einfuegen-->

<div>
    <a href="/offer"><p class="fa fa-chevron-left"></p> zurück</a>
</div>
<h1>Details für Haus <?php echo $house->getName(); ?></h1>
<table>
    <tr>
        <td>Name der Anlage</td>
        <td><p><?php echo $house->getName(); ?></p></td>
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
    <tr>
        <td>Foto</td>
        <td>
            <img src="<?php echo "/images/" . $house->getFrontimage(); ?>" style="width: 100px;height: 100px" alt="alt">
        </td>
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

<script src="/scripts/offer-detail.js"></script>
