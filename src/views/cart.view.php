<?php
global $message;
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Warenkorb";
$booking = $param["booking"] ?? null;
$bpos = $param["bookingpositions"] ?? null;
include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->
    <h1>Warenkorb</h1>
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
                <td><?php echo $p->getDateStart() ?></td>
            </tr>
            <tr>
                <td>Bis</td>
                <td><?php echo $p->getDateEnd() ?></td>
            </tr>
            <tr>
                <td>Price Details</td>
                <td><?php print_r($p->getPriceDetailList()); ?></td> <!-- todo : expand list in detail -->
            </tr>
            <tr>
                <td>House id</td>
                <td><?php echo $p->getHouseId() ?></td>
            </tr>
            <?php
        }
        echo "</table>";
        echo "<a href='/checkout'><button type='submit'>Zur Kasse</button></a>";
    } else {
        // if no booking exists
        echo "<h3>Der Warenkorb ist leer. Suche jetzt nach dem Ferienhaus deiner Träume! => <a href='/dashboard'>Zur Suche</a></h3>"; // todo : design (Marvin)
    }
    ?>
</div>
<!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>