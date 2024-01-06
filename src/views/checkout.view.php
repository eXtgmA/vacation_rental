<?php
$header = __DIR__ . "/partials/header.view.php";
// Titel der Seite eintragen
$title = "Kasse";
$booking = $param["booking"] ?? null;
$bpos = $param["bookingpositions"] ?? [];
$houses = $param["houses"] ?? [];
$priceSum = 0;
include_once($header);

// auto redirect if there is nothing in the cart
if (!(isset($booking) && !empty($bpos) && !empty($houses))) {
    redirect('/cart', 302);
    die();
}
?>
    <link rel="stylesheet" href="/styles/checkout.css"/>
    <div class="content-layout">
        <div class="headline">
            <h1>Kasse</h1>
        </div>
        <?php foreach ($bpos as $key => $p) { ?>
            <?php $house = $houses[$p->getHouseId()] ?>
            <div class="cart-entry-grid">
                <div class="item-headline">
                    <h2><?php echo $house->getName() ?></h2>
                </div>
                <div class="item-image">
                    <img src="<?php echo "/images/" . $house->getFrontimage(); ?>" alt="[alt]">
                </div>
                <div class="item-location">
                    <div class="informations">
                        <div class="information">
                            <span class="information-key">Ort:</span>
                            <span class="information-value"><?php echo $house->getPostalCode() . ' ' . $house->getCity() ?></span>
                        </div>
                        <div class="information">
                            <span class="information-key">Straße:</span>
                            <span class="information-value"><?php echo $house->getStreet() . ' ' . $house->getHouseNumber() ?></span>
                        </div>
                    </div>
                    <hr/>
                </div>
                <div class="item-duration">
                    <div class="informations">

                        <div class="information">
                            <span class="information-key">Von:</span>
                            <span class="information-value date-value"><?php echo $p->getDateStart() ?></span>
                        </div>
                        <div class="information">
                            <span class="information-key">Bis:</span>
                            <span class="information-value date-value"><?php echo $p->getDateEnd() ?></span>
                        </div>
                        <div class="information price">
                            <span class="information-key">Preis / Nacht:</span>
                            <span class="information-value"><?php echo $house->getPrice(); ?>€</span>
                        </div>
                    </div>
                    <hr/>
                </div>
                <?php $pdl = json_decode($p->getPriceDetailList(), true) ?>
                <?php if (!empty($pdl) && isset($pdl['options'])) { //@phpstan-ignore-line
                    $optionSum = 0; ?>
                    <div class="item-options">
                        <h3>Optionen</h3>
                        <?php foreach ($pdl['options'] as $name => $price) { //@phpstan-ignore-line ?>
                            <div class="option">
                                <span class="option-key"><?php echo $name //@phpstan-ignore-line ?></span>
                                <span class="option-value"><?php echo $price //@phpstan-ignore-line ?>€</span>
                            </div>
                            <?php $optionSum += $price ?>
                        <?php } ?>
                        <hr/>
                        <div class="option-sum option">
                            <span class="option-key">Summe der Optionen</span>
                            <span class="option-value"><?php echo $optionSum ?>€</span>
                        </div>
                        <hr/>
                    </div>
                <?php } else { ?>
                    <!-- if no options are provided -->
                    <div class="item-options <?php echo !empty($pdl) ? '' : 'hide' ?>">
                        <h3>Optionen</h3>
                        <div class="option">
                            <span class="option-key">keine ausgewählt</span>
                            <span class="option-value">0€</span>
                        </div>
                        <hr/>
                        <div class="option-sum option">
                            <span class="option-key">Summe der Optionen</span>
                            <span class="option-value">0€</span>
                        </div>
                        <hr/>
                    </div>
                <?php } ?>
                <div class="item-nights">
                    <div class="nights">
                        <span class="nights-key">Anzahl der Nächte:</span>
                        <span class="nights-value"><?php echo $pdl['night_count'] //@phpstan-ignore-line ?>x</span>
                    </div>
                    <div class="nights">
                        <span class="nights-key">Nächte * Preis/Nacht</span>
                        <span class="nights-value"><?php echo($pdl['night_count'] * $pdl['price_per_night']) //@phpstan-ignore-line ?>€</span>
                    </div>
                    <hr/>
                </div>
                <div class="item-price">
                    <div class="price">
                        <span class="price-label">Preis:</span>
                        <span class="price-value"><?php echo $pdl["total_price"]; //@phpstan-ignore-line
                            $priceSum += $pdl['total_price']; //@phpstan-ignore-line ?>€</span>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="price-footer">
            <span class="price-label">Gesamtpreis:</span>
            <span class="price-value"><?php echo $priceSum; ?>€</span>
        </div>
        <div class="pre-footer">
            <button class="btn-secondary" type="button" onclick="openLink('/cart')">zurück zum Warenkorb</button>
            <form action='<?php echo "/checkout/booking/" . $booking->getId(); ?>' method='post'>
                <button class="btn-primary" type="submit">Bezahlen</button>
            </form>
        </div>
    </div>

<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>