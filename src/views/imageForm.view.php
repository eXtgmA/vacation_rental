<?php
$header=__DIR__."/partials/header.view.php";
// Titel der Seite eintragen
$title = "Biler";
$images = isset($param) ? $param : null;
$path=__DIR__."/../../public/images/";

include_once($header);
?>
<!--Hier den HTML Inhalt einfuegen-->
<h1>Hier werden Bilder hochgeladen</h1>
    <form action="/image/save" method="POST" enctype="multipart/form-data">
        <label for="image" ><i class="fa fa-plus"></i> <i class="fa fa-camera"></i></label>
        <input type="file" name="image" hidden="hidden" id="image">
        <button type="submit" name="uloadfile">Abschicken</button>
    </form>
<?php foreach ($images as $image) {
    ?>
    <img src="<?php print "/images/".$image[0]; ?>" alt="not found" style="width: 40px;height: 40px><i class="fa fa-camera"></i>
    <form action="/image/delete" method="POST" style="display: inline-block">
        <input type="text" value="<?php print $image[0]; ?>" name="uuid" hidden="hidden">
        <button type="submit"><i class="fa fa-delete-left"></i></button>
    </form>
    <?php
}
?>
<!--Ende HTML Inhalt-->
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>