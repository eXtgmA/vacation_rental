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
        echo "<table>"
        ?>
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
            <th>Detail</th>
            <th>Front</th>
        </tr>
        <?php
        foreach ($param as $item){
            echo "<tr>";
            echo "<td>" . $item->getName() . "</td>";
            echo "<td>" . $item->getSquareMeter() . "</td>";
            echo "<td>" . $item->getRoomCount() . "</td>";
            echo "<td>" . $item->getMaxPerson() . "</td>";
            echo "<td>" . $item->getPrice() . "</td>";
            echo "<td>" . $item->getPostalCode() . "</td>";
            echo "<td>" . $item->getCity() . "</td>";
            echo "<td>" . $item->getStreet() . "</td>";
            echo "<td>" . $item->getHouseNumber() . "</td>";
            echo "<td>" . $item->getIsDisabled() . "</td>";
            echo "<td><a href='/offer/show/" . $item->getId() . "'><i class='fa fa-house'></i></a></td>";
            ?>
        </td>
        <td><img src="/images/<?php print $item->getFrontImage() ?>" style="width: 30px;height: 30px" alt="alt"></td>
            <td>
            <form action="/offer/togglestatus/<?php echo $item->getId(); ?>" method="post">
                <button type="submit"><i class="fa <?php $item->getIsDisabled()==1 ? print('fa-eye-slash') : print('fa-eye')?>"></i></button>
            </form>
            <?php
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
