<?php
$header=__DIR__."/partials/header.view.php";
$title = "dashboard";
$site = "dashboard";
include_once($header);
?>
<div style="text-align: center">
<h2>Finde dein Traumhaus für den Perfekten Urlaub</h2>
    <div>
        <div style="display: inline-block;text-align: left" >
        <label style="display: block" for="Reiseziel">Reiseziel</label>
        <input  name="Reiseziel" type="text">
        </div>
        <div style="display: inline-block">
        <label for="Anreise" style="display: block;text-align: left">Anreise</label>
        <input name="Anreise" type="date">
        </div>
            <div style="display: inline-block">
        <label for="Abreise" style="display: block;text-align: left">Abreise</label>
        <input name="Abreise" type="date">
            </div>
                <div style="display: inline-block">
        <label for="Personen" style="display: block;text-align: left">Personen</label>
        <input name="Personen" type="number">
                </div>
    </div>
    <div style="margin: 10px">
        <button>Starte Suche</button>
    </div>
    <div style="text-align: center">
        <h4 style="margin-top: 30px">Entdecke auch mal was neues</h4>
        <div>
            <img src="/images/haus1.jpg" style="width: 150px;margin: 20px" alt="">
            <img src="/images/haus2.jpg" style="width: 150px;margin: 20px" alt="">
            <img src="/images/haus3.jpg" style="width: 150px;margin: 20px" alt="">
        </div>
</div>
<?php
$footer=__DIR__."/partials/footer.view.php";
include_once($footer)
?>