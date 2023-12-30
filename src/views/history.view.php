<?php
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Buchungsverlauf";
$bookings = $param["bookings"] ?? [];
$bpos = $param["bookingpositions"] ?? [];
$houses = $param["houses"] ?? [];
include_once($header);
?>
    <link rel="stylesheet" href="/styles/history.css"/>
    <div class="headline">
        <h1>Buchungsverlauf</h1>
        <button class="btn btn-secondary" type="button" onclick="openLink('/profile')" style="margin-right: 20px">Zurück</button>
    </div>
<?php if (!empty($bookings) && !empty($bpos) && !empty($houses)) { ?>
    <?php foreach ($bookings as $booking) {
        $priceSum = 0;
        $date = new DateTime(($booking->getBookedAt() ?? "")); ?>

        <div style='border: solid green;'>
        <h2>Buchung vom <?php echo $date->format('d.m.Y') ?></h2>
        <p>Positionen: <?php echo count($bpos[$booking->getId()]); ?></p><br>

        <table>

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
                        <span class="nights-value"><?php echo ($pdl['night_count']*$pdl['price_per_night']) //@phpstan-ignore-line ?>€</span>
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
        </table>
        </div>
    <?php } ?>
<?php } else { ?>
    <h3>Sie haben noch nichts gebucht. Suchen Sie jetzt nach dem Ferienhaus Ihrer Träume! => <a href='/dashboard'>Zur Suche</a></h3>
<?php } ?>

<!-- old Design below -->

    <button class="btn btn-secondary" type="button" onclick="openLink('/profile')" style="margin-right: 20px">Zurück</button>
    <h1>Buchungsverlauf</h1>
<div>
    <?php if (!empty($bookings) && !empty($bpos) && !empty($houses)) {
        /** @var \src\models\Booking $booking */
        foreach ($bookings as $booking) {
            $priceSum = 0;

            echo "<div style='border: solid orange;'>";
                $date = new DateTime(($booking->getBookedAt() ?? ""));
                echo "<h2>Buchung vom {$date->format('d.m.Y')}</h2>";
                $count = count($bpos[$booking->getId()]);
                echo "<p>Positionen: {$count}</p><br>";

                echo "<table>";
                /** @var \src\models\Bookingposition $p */
            foreach ($bpos[$booking->getId()] as $p) {
                if (isset($houses[$p->getHouseId()])) { // if house exists
                    /** @var \src\models\House $house */
                    $house = $houses[$p->getHouseId()];
                    ?><tr>
                        <td><h3><?php echo $house->getName() ?></h3></td>
                        </tr>
                        <td>
                            <img src="<?php echo "/images/".$house->getFrontimage();?>" style="width: 100px;height: 100px" alt="[alt]">
                        </td>
                        <tr>
                            <td>Ort</td>
                            <td><?php echo $house->getPostalCode(). ", " .$house->getCity() ?></td>
                        </tr>
                        <tr>
                            <td>Strasse</td>
                            <td><?php echo $house->getStreet() ." ". $house->getHouseNumber() ?></td>
                        </tr>
                <?php } else { // if house does not exist anymore
                    ?><tr>
                        <td><h3>Dieses Haus gibt es nicht mehr</h3></td>
                        </tr>
                        <td>
                            <img src="" style="width: 100px;height: 100px" alt="[alt]"><!-- todo : insert default image -->
                        </td>
                        <tr>
                            <td>Ort</td>
                            <td>unbekannt</td>
                        </tr>
                        <tr>
                            <td>Strasse</td>
                            <td>unbekannt</td>
                        </tr>
                        <?php
                }?>
                    <tr>
                        <td>Von</td>
                        <td><?php echo $p->getDateStart() ?></td>
                    </tr>
                    <tr>
                        <td>Bis</td>
                        <td><?php echo $p->getDateEnd() ?></td>
                    </tr>
                    <tr>
                        <td>Optionen</td>
                        <td>
                        <?php $pdl = json_decode($p->getPriceDetailList(), true);
                        $optionSum = 0;
                        if (!empty($pdl)) {
                            echo "<table>";
                            foreach ($pdl['options'] as $name => $price) { // @phpstan-ignore-line
                                echo "<tr><td>" . $name." => ".$price."€" . "</td></tr>";
                                $optionSum += $price;
                            }
                            echo "</table>";
                        }
                        echo "Summe der Optionen => {$optionSum}€"; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Preis</td>
                        <td><?php echo ($pdl["price"] ?? "Price should be displayed here")."€"; // @phpstan-ignore-line
                        $priceSum += $pdl['price'] ?? 0; // @phpstan-ignore-line?></td>
                    </tr>
                    <?php
            }
                echo "</table>";
            echo "</div>";
        }
    } else {
        // if no booking exists
        echo "<h3>Sie haben noch nichts gebucht. Suchen Sie jetzt nach dem Ferienhaus Ihrer Träume! => <a href='/dashboard'>Zur Suche</a></h3>"; // todo : make me pretty (Marvin)
    }
    ?>
</div>
<!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>