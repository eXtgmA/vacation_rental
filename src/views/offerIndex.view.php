<?php
$header = __DIR__ . "/partials/header.view.php";
// Titel der Seite eintragen
$title = "";
$page = 'offerindex';
include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->
<a href="/offer/create">Neues Haus anlegen</a>

<div>
    Da hier sind deine Häuser:

    <?php
    if (isset($param)) {
    ?>
    <table>
        <tr>
            <th>Name</th>
            <th>Quadratmeter</th>
            <th>Anzahl Zimmer</th>
            <th>Max Personen</th>
            <th>Preis</th>
            <th>Postleitzahl</th>
            <th>Stadt</th>
            <th>Straße</th>
            <th>Hausnummer</th>
            <th>status</th>
        </tr>
        <?php
        /** @var \src\models\House[] $param */
        foreach ($param as $house) {
            echo "<tr>";
            echo "<td>" . $house->getName() . "</td>";
            echo "<td>" . $house->getSquareMeter() . "</td>";
            echo "<td>" . $house->getRoomCount() . "</td>";
            echo "<td>" . $house->getMaxPerson() . "</td>";
            echo "<td>" . $house->getPrice() . "</td>";
            echo "<td>" . $house->getPostalCode() . "</td>";
            echo "<td>" . $house->getCity() . "</td>";
            echo "<td>" . $house->getStreet() . "</td>";
            echo "<td>" . $house->getHouseNumber() . "</td>";
            echo "<td>" . $house->getIsDisabled() . "</td>";
            echo "<td><a href='/offer/show/" . $house->getId() . "'><i class='fa fa-house'></i></a></td>";
            ?>
            <td>
            <form action="/offer/togglestatus/<?php echo $house->getId(); ?>" method="post">
                <button type="submit"><i class="fa <?php $house->getIsDisabled()==1 ? print('fa-eye-slash') : print('fa-eye')?>"></i></button>
            </form>
            </td><?php
        }
        echo "</table>";
        }
        ?>


</div>
<!--Ende HTML Inhalt-->
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>
