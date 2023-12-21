<?php
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Warenkorb";
$booking = $param["booking"] ?? null;
$bpos = $param["bookingpositions"] ?? [];
$houses = $param["houses"] ?? [];
$availabilityError = $_SESSION['availabilityError'] ?? [];
include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->
    <h1>Warenkorb</h1>
<div>
    <?php if (isset($booking) && !empty($bpos) && !empty($houses)) {
        echo "<table>";
        /** @var \src\models\Bookingposition $p */
        foreach ($bpos as $key => $p) {
            /** @var \src\models\House $house */
            $house = $houses[$p->getHouseId()];
            ?>
            <tr>
                <td><h3><?php echo $house->getName() ?></h3></td>
                <?php if (in_array($p->getId(),$availabilityError)) {
                    echo "<td><h3 style='color: red'>Ausgebucht! Wird beim Verlassen der Seite aus dem Warenkorb gelöscht!</h3></td>";
                }?>
            </tr>
            <tr>
                <td></td>
                <?php if (!in_array($p->getId(),$availabilityError)) { ?>
                <td><form action="<?php echo '/booking/delete/'.$p->getId(); ?>" method="post"><button type="submit">entfernen</button></form></td>
                    <!-- todo : make this card a link to /booking/create/$p->getHouseId() -->
                <?php } ?>
            </tr>
            <td>
                <img src="<?php echo "/images/".$house->getFrontimage();?>" style="width: 100px;height: 100px" alt="[alt]">
            </td>
            <tr>
                <td>Von</td>
                <td><?php echo $p->getDateStart() ?></td>
            </tr>
            <tr>
                <td>Bis</td>
                <td><?php echo $p->getDateEnd() ?></td>
            </tr>
            <tr>
                <td>Preis</td>
                <td><?php echo $house->getPrice()." Euro/Nacht" ?></td>
            </tr>
            <tr>
                <td>Ort</td>
                <td><?php echo $house->getPostalCode(). ", " .$house->getCity() ?></td>
            </tr>
            <tr>
                <td>Strasse</td>
                <td><?php echo $house->getStreet() ." ". $house->getHouseNumber() ?></td>
            </tr>
            <tr>
                <td>Personen</td>
                <td><?php echo "max.".$house->getMaxPerson() ?></td>
            </tr>
            <tr>
                <td>Fläche</td>
                <td><?php echo $house->getSquareMeter() ?></td>
            </tr>
            <tr>
                <td>Räume</td>
                <td><?php echo $house->getRoomCount() ?></td>
            </tr>
            <?php if (in_array($p->getId(), $availabilityError)) {
                $p->deleteBookingposition();
            }
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