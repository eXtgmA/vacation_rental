<?php
global $message;
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Kasse";
$booking = $param["booking"] ?? null;
$bpos = $param["bookingpositions"] ?? null;
include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->
    <h1>Kasse</h1>
    <?php
    echo($message ?? "<h1>$message</h1>");
    ?>
<div>
    <?php if (isset($booking) && isset($bpos)) {
        echo "<table>";
        /** @var \src\models\Bookingposition $p */
        foreach ($bpos as $key => $p) {
            ?>
            <tr>
                <td>Von</td>
                <td><?php echo $p->getDateStart(); ?></td>
            </tr>
            <tr>
                <td>Bis</td>
                <td><?php echo $p->getDateEnd(); ?></td>
            </tr>
            <tr>
                <td>Optionen</td>
<!--                <td>--><?php //echo $p->getPriceDetailList(); ?><!--</td>-->
            </tr>
            <tr>
                <td>Preis</td>
                <td>XXXX,xx Euro<?php // echo $p["price_detail_list"]["price"]; ?></td>
            </tr>
            <?php
        }
        echo "</table>";
        ?>
        <h2>Gesamtpreis: XXXX,xx Euro</h2>
        <form action='<?php "/booking/checkout/".$booking->getId(); ?>' method='post'>
            <input type="hidden" id="gesamtpreis" name="gesamtpreis" value=""> <!-- todo : calculate value with JS (sum of all prices) -->
            <button type="submit">Bezahlen</button>
        </form>
        <?php
    } else {
        // if no booking exists
        echo "<h3>Hier gibt es nichts zu bezahlen. Suche jetzt nach dem Ferienhaus deiner Träume! => <a href='/dashboard'>Zur Suche</a></h3>";
    }
    ?>
</div>
<!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>