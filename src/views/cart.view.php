<?php
$header = __DIR__ . "/partials/header.view.php";
// Titel der Seite eintragen
$title = "Warenkorb";
$booking = $param["booking"] ?? null;
$bpos = $param["bookingpositions"] ?? [];
$houses = $param["houses"] ?? [];
$availabilityError = $_SESSION['availabilityError'] ?? [];
include_once($header);
?>
    <link rel="stylesheet" href="/styles/cart.css"/>
    <div class="headline">
        <h1>Warenkorb</h1>
    </div>
<?php if (isset($booking, $bpos, $houses)) { ?>
    <?php foreach ($bpos as $key => $p) { ?>
        <?php $house = $houses[$p->getHouseId()] ?>
            <?php if (in_array($p->getId(), $availabilityError)) { ?>
            <div class="" id="cart-entry-grid" style="background-color: lightgray; cursor: pointer" onclick="openLink('/booking/create/<?php echo $p->getHouseId() ?>')"> <!-- todo : set onclick link to /booking/edit/$p->getHouseId() -->
                <div class="item-headline" style="flex-direction: column">
                    <h2 style='color: red;'>Ausgebucht! Wird beim Verlassen der Seite aus dem Warenkorb gelöscht!</h2>
                    <h2><?php echo $house->getName() ?></h2>
                </div>
            <?php } else { ?>
            <div class="" id="cart-entry-grid">
                <div class="item-headline">
                    <h2><?php echo $house->getName() ?></h2>
                </div>
            <?php } ?>
            <div class="item-image">
                <img src="<?php echo "/images/" . $house->getFrontimage(); ?>" alt="[alt]">
            </div>
            <div class="item-total-price">
                <p><?php echo json_decode($p->getPriceDetailList(), true)['total_price'] //@phpstan-ignore-line ?>€</p>
            </div>
            <div class="item-date-start information">
                <span class="information-key">Von:</span>
                <span class="information-value date-value"><?php echo $p->getDateStart() ?></span>
                <hr/>
            </div>
            <div class="item-date-end information">
                <span class="information-key">Bis:</span>
                <span class="information-value date-value"><?php echo $p->getDateEnd() ?></span>
                <hr/>
            </div>
            <div class="item-price information">
                <span class="information-key">Preis pro Nacht:</span>
                <span class="information-value"><?php echo $house->getPrice(); ?>€</span>
                <hr/>
            </div>
            <div class="item-city information">
                <span class="information-key">Ort:</span>
                <span class="information-value"><?php echo $house->getPostalCode() . ' ' . $house->getCity() ?></span>
                <hr/>
            </div>
            <div class="item-street information">
                <span class="information-key">Straße:</span>
                <span class="information-value"><?php echo $house->getStreet() . ' ' . $house->getHouseNumber() ?></span>
                <hr/>
            </div>
            <div class="item-max-person information">
                <span class="information-key">Max. Personen<span class="additional-text">anzahl:</span></span>
                <span class="information-value"><?php echo $house->getMaxPerson() ?></span>
                <hr/>
            </div>
            <div class="item-square-meter information">
                <span class="information-key">Wohnfläche:</span>
                <span class="information-value"><?php echo $house->getSquareMeter() ?>m²</span>
                <hr/>
            </div>
            <div class="item-room-count information">
                <span class="information-key">Raumanzahl:</span>
                <span class="information-value"><?php echo $house->getRoomCount() ?></span>
                <hr/>
            </div>
            <?php if (in_array($p->getId(), $availabilityError)) { ?>
                <!-- booking is not available anymore and will be deleted -->
                <div class="item-edit">
                    <button type="button" class="btn-secondary" disabled>Bearbeiten</button>
                </div>
                <div class="item-delete">
                    <button type="submit" class="btn-secondary" onclick="sendPostRequest('<?php echo "/booking/delete/".$p->getId(); ?>')" disabled>Entfernen</button>
                </div>
                <?php $p->deleteBookingposition(); ?>
            <?php } else { ?>
                <div class="item-edit">
                    <button type="button" class="btn-secondary">Bearbeiten</button>
                </div>
                <div class="item-delete">
                    <button type="submit" class="btn-secondary" onclick="sendPostRequest('<?php echo "/booking/delete/".$p->getId(); ?>')">Entfernen</button>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    <div class="price-footer">
        <button type="submit" class="btn-primary" onclick="openLink('/checkout')">Zur Kasse</button>
    </div>
<?php } else { ?>
    <div>
        <h2 style="display: flex; justify-content: center">Der Warenkorb ist leer</h2>
    </div>
<?php } ?>

<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>