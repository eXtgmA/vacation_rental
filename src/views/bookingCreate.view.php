<?php
global $message;
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Haus buchen";
$house = $param["house"] ?? null;
$options = $param["options"] ?? null;
$bookedDays = $param["bookedDays"] ?? null;
include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->
    <h1>Haus buchen</h1>
    <?php
    echo($message ?? "<h1>$message</h1>");
    ?>
<div style="justify-content: center">
    <img src="/images/<?php print $house->getFrontImage(); ?>" style="width: 300px;height: 200px" alt="alt">
</div>
<div>
    <form action="/booking/create/" method="post">
        <table>
            <tr>
                <td>Buchungsauslastung</td>
                <!-- todo (Marvin) insert calender showing booked dates -->
            </tr>
            <tr>
                <td><label for="dateStart">Von</label></td>
                <td><input type="date" id="dateStart" name="dateStart" value="<?php prefill('dateEnd') ?>" required></td>
            </tr>
            <tr>
                <td><label for="dateEnd">Bis</label></td>
                <td><input type="date" id="dateEnd" name="dateEnd" value="<?php prefill('dateEnd') ?>" required></td>
            </tr>
            <tr>
                <td>Zusatzoptionen</td>
                <td><?php print_r($options) ?></td> <!-- todo checkboxes for options -->
            </tr>
            <tr>
                <td>Gesamtpreis</td>
                <td><?php prefill('price') ?></td> <!-- todo JS (Marvin) add live calculation for Gesamtpreis -->
            </tr>
            <tr>
                <input type="hidden" id="houseId" name="houseId" value=<?php echo $house->getId(); ?>>
            </tr>
        </table>
        <button type="submit">Zum Warenkorb hinzufügen</button>
    </form>
</div>
<!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>