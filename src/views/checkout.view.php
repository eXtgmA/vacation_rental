<?php
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Kasse";
$booking = $param["booking"] ?? null;
$bpos = $param["bookingpositions"] ?? null;
$houses = $param["houses"] ?? null;
include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->
    <h1>Kasse</h1>
    <?php
    echo($message ?? "<h1>$message</h1>"); // @phpstan-ignore-line
    ?>
<div>
    <?php if (isset($booking, $bpos, $houses)) {
        echo "<table>";
        /** @var \src\models\Bookingposition $p */
        foreach ($bpos as $key => $p) {
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
                <td><?php echo ($pdl["price"] ?? "Price not calculated!")."€"; // @phpstan-ignore-line?></td> <!-- todo : get price from list (and delete stan-ignore) -->
            </tr>
            <?php
        }
        echo "</table>";
        ?>
        <h2>Gesamtpreis: XXXX,xx Euro</h2>
        <form action='<?php echo "/checkout/booking/".$booking->getId(); ?>' method='post'>
            <input type="hidden" id="gesamtpreis" name="gesamtpreis" value=""> <!-- todo : calculate value with JS (sum of all prices) -->
            <a href="/cart"><button type="button">zurück zum Warenkorb</button></a>
            <button type="submit">Bezahlen</button>
        </form>
        <?php
    } else {
        // if no booking exists
        echo "<h3>Hier gibt es nichts zu bezahlen. Suche jetzt nach dem Ferienhaus deiner Träume! => <a href='/dashboard'>Zur Suche</a></h3>"; // todo : make me pretty (Marvin)
    }
    ?>
</div>
<!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>