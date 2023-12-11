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
        foreach ($bpos as $key => $p) {
            ?>
            <tr>
                <td>Von</td>
                <td><?php echo $p["date_start"]; ?></td>
            </tr>
            <tr>
                <td>Bis</td>
                <td><?php echo $p["date_end"]; ?></td>
            </tr>
            <tr>
                <td>Price Details</td>
                <td><?php echo $p["price_detail_list"]; ?></td>
            </tr>
            <tr>
                <td>House id</td>
                <td><?php echo $p["house_id"]; ?></td>
            </tr>
            <?php
        }
        echo "</table>";
        echo "<a href='/booking/checkout/".$booking->getId()."'><button type='submit'>Zur Kasse</button></a>";
    } else {
        // if no booking exists
        echo "<h3>Der Warenkorb ist leer. Suche jetzt nach dem Ferienhaus deiner Träume! => <a href='/dashboard'>Zur Suche</a></h3>";
    }
    ?>
</div>
<!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>