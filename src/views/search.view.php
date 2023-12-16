<?php
$header = __DIR__ . "/partials/header.view.php";
$title = "dashboard";
$site = "dashboard";
$houses = null;
if (isset($param)) {
    if($param['houses']){
        $houses = $param['houses'];
    }
}
include_once($header);
?>
    <link rel="stylesheet" href="/styles/dashboard.css"/>
    <div id="dashboard-grid">
        <div id="title">
            <h1>Finde dein Traumhaus für den perfekten Urlaub</h1>
        </div>
        <div id="search">
            <form action="/offer/find">
            <div id="destination-search" class="search-input" style="display: inline-block;text-align: left">
                <label id="destination-label" style="display: block" for="destination-input-field">Reiseziel</label>
                <input id="destination-input-field" class="input-field" name="destination" type="text">
            </div>
            <div id="from-date-search" class="search-input" style="display: inline-block">
                <label id="from-date-label" for="from-date-input-field"
                       style="display: block;text-align: left">Anreise</label>
                <input id="from-date-input-field" class="input-field" name="dateStart" type="date">
            </div>
            <div id="to-date-search" class="search-input" style="display: inline-block">
                <label id="to-date-label" for="to-date-label-input-field" style="display: block;text-align: left">Abreise</label>
                <input id="to-date-label-input-field" class="input-field" name="dateEnd" type="date">
            </div>
            <div id="person-amount-search" class="search-input" style="display: inline-block">
                <label id="person-amount-label" for="person-amount-input-field" style="display: block;text-align: left">Personen</label>
                <input id="person-amount-input-field" class="input-field" name="persons" type="number">
            </div>
            <div style="margin: 10px">
                <button class="btn-secondary">Ferienhaus suchen</button>
            </div>
            </form>
        </div>
    </div>
<?php
foreach ($houses as $house) {
    ?>
<div class="card">
    <?php print $house->getName() ?>
</div>
    <?php
}
?>

<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>
