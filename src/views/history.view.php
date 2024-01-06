<?php
$header = __DIR__ . "/partials/header.view.php";
// Titel der Seite eintragen
$title = "Buchungsverlauf";
$bookings = $param["bookings"] ?? [];
$bpos = $param["bookingpositions"] ?? [];
$houses = $param["houses"] ?? [];
include_once($header);
?>
    <link rel="stylesheet" href="/styles/history.css"/>
    <div class="content-layout">
        <div class="headline">
            <h1 style="word-break: normal">Buchungs&shy;verlauf</h1>
            <button class="btn btn-secondary" type="button" onclick="openLink('/profile')" style="margin-right: 20px">Zurück zur Profilseite</button>
        </div>
        <?php if (!empty($bookings) && !empty($bpos) && !empty($houses)) { ?>
            <?php foreach ($bookings as $booking) {
                $priceSum = 0;
                $date = new DateTime(($booking->getBookedAt() ?? "")); ?>

                <div class="booking-entry">
                    <div class="booking-headline">
                        <h2>Buchung vom <?php echo $date->format('d.m.Y') ?></h2>
                    </div>
                    <div class="positions">
                        <span>Positionen: </span>
                        <span> <?php echo count($bpos[$booking->getId()]); ?></span>
                    </div>

                    <?php foreach ($bpos[$booking->getId()] as $p) { ?>
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
                                <div class="item-options">
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
                                    <span class="nights-value"><?php echo $pdl['night_count'] ?? 'unknown '; //@phpstan-ignore-line ?>x</span>
                                </div>
                                <div class="nights">
                                    <span class="nights-key">Nächte * Preis/Nacht</span>
                                    <span class="nights-value"><?php if (isset($pdl['night_count'], $pdl['price_per_night'])) { //@phpstan-ignore-line
                                            echo($pdl['night_count'] * $pdl['price_per_night']);
                                                               } else {
                                                                   echo 'unknown ';
                                                               } ?>€</span>
                                </div>
                                <hr/>
                            </div>
                            <div class="item-price">
                                <div class="price">
                                    <span class="price-label">Preis:</span>
                                    <span class="price-value"><?php echo $pdl["total_price"] ?? 'unknown '; //@phpstan-ignore-line
                                        $priceSum += $pdl['total_price'] ?? 0; //@phpstan-ignore-line ?>€</span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="price-sum">
                        <span class="price-sum-label">Gesamtpreis: </span>
                        <span class="price-sum-value"> <?php echo $priceSum; ?>€</span>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="no-bookings">
                <h3>Du hast bisher noch keine Buchungen getätigt. Suche jetzt nach dem Ferienhaus deiner Träume! <i class="fa-solid fa-plane-departure"></i></h3>
                <button class="btn-secondary" type="button" onclick="openLink('/dashboard')">Zur Suche</button>
            </div>
        <?php } ?>
    </div>
    <!--Ende HTML Inhalt-->
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>