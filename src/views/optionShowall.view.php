<?php
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "All Options";
// set "zurück"-button target via house_id
if (isset($param["house_id"])) {
    $house_reference = "/offer/show/".$param["house_id"];
    $house_id = $param["house_id"];
    unset($param["house_id"]);
} else {
    $house_reference = "/offer";
    $house_id = "#";
}
?>
<!--Hier den HTML Inhalt einfuegen-->
<div>
    <a href="<?php echo $house_reference; ?>"><p class="fa fa-chevron-left"></p> zurück</a>
</div>
<div>
    <a href="<?php echo "/option/create/".$house_id;?>"><h3>Create Option</h3></a>
</div>
<div>
    <h1>Alle angelegten Optionen</h1>
    <?php
    if (isset($param)) {
        echo "<table>"
        ?>
        <tr>
            <th>Name</th>
            <th>Beschreibung</th>
            <th>Preis</th>
            <th>Status</th>
            <th>Foto</th>
        </tr>
        <?php
        /** @var \src\models\Option[] $param */
        foreach ($param as $option) {
            echo "<tr>";
            echo "<td>" . $option->getName() . "</td>";
            echo "<td>" . $option->getDescription() . "</td>";
            echo "<td>" . $option->getPrice() . "</td>";
            echo "<td>" . $option->getIsDisabled() . "</td>";
            ?>
            <td><img src="/images/<?php print $option->getOptionImage() ?>" style="width: 30px;height: 30px" alt="alt"></td>
            <!-- todo: toggle status on and off
            <td>
                <form action="/offer/togglestatus/<?php // echo $option->getId(); ?>" method="post">
                    <button type="submit"><i class="fa <?php // $option->getIsDisabled()==1 ? print('fa-eye-slash') : print('fa-eye')?>"></i></button>
                </form>
            </td>
            -->
            <?php
        }
        echo "</table>";
    }
    ?>
</div>
<!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>