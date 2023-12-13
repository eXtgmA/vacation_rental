<?php
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Datailseite für ein hausobject";
$house = isset($param) ? $param : null;
include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->
<div>
    <a href="/offer"><p class="fa fa-chevron-left"></p> zurück</a>
</div>
<h1>Details für Haus <?php echo $house->getName();?></h1>
<table>
    <tr>
        <form action="/offer/edit/<?php echo $house->getId()?>" method="POST">

        <td>Name der Anlage</td>
        <td><p><input type="text" name="name" value="<?php echo $house->getName();?>"></p></td>
            <button type="submit">senden</button>
        </form>
    </tr>
    <tr>
        <td>Beschreibung</td>
        <td><p><?php echo $house->getDescription();?></p></td>
    </tr>
    <tr>
        <td>Quadratmeter</td>
        <td><p><?php echo $house->getSquareMeter();?></p></td>
    </tr>
    <tr>
        <td>Anzahl mögliche Personen</td>
        <td><p><?php echo $house->getMaxPerson();?></p></td>

    <tr>
        <td>Anzahl Räume</td>
        <td><p><?php echo $house->getRoomCount();?></p></td>
    </tr>
    <tr>
        <td>Preis pro Nacht</td>
        <td><p><?php echo $house->getPrice();?></p></td>
    </tr>
    <tr>
        <td>Postleitzahl</td>
        <td><p><?php echo $house->getPostalCode();?></p></td>
    </tr>
    <tr>
        <td>Ort</td>
        <td><p><?php echo $house->getCity();?></p></td>
    </tr>
    <tr>
        <td>Straße</td>
        <td><p><?php echo $house->getStreet();?></p></td>
    </tr>
    <tr>
        <td>Hausnummer</td>
        <td><p><?php echo $house->getHouseNumber();?></p></td>
    </tr>
    <tr>
        <td>Foto</td>
        <td>
            <img src="<?php echo "/images/".$house->getFrontimage();?>" style="width: 100px;height: 100px" alt="alt">
        </td>
    </tr>
    <tr>
        <td>Status</td>
        <td><p><?php echo $house->getIsDisabled();?></p></td>
    </tr>
</table>
<div>
    <a href="<?php echo "/option/showall/".$house->getId();?>"><h1>Manage Options</h1></a>
</div>
<!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>