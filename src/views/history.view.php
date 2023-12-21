<?php
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Buchungsverlauf";
$bookings = $param["bookings"] ?? [];
$bpos = $param["bookingpositions"] ?? [];
$houses = $param["houses"] ?? [];
include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->
    <button class="btn btn-secondary" type="button" onclick="openLink('/profile')" style="margin-right: 20px">Zurück</button>
    <h1>Buchungsverlauf</h1>
<div>
    <?php if (!empty($bookings) && !empty($bpos) && !empty($houses)) {
        /** @var \src\models\Booking $booking */
        foreach ($bookings as $booking) {
            $priceSum = 0;

            echo "<div style='border: solid orange;'>";
                $date = new DateTime($booking->getBookedAt());
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
                        <td><?php echo ($pdl["price"] ?? "Price should be displayed here")."€"; $priceSum += $pdl['price'] ?? 0; // @phpstan-ignore-line?></td>
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