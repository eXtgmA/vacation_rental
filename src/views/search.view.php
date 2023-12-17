<?php
$header = __DIR__ . "/partials/header.view.php";
$title = "dashboard";
$site = "dashboard";
$houses = null;
if (isset($param)) {
    if ($param['houses']) {
        $houses = $param['houses'];
    }
}
include_once($header);
?>
    <link rel="stylesheet" href="/styles/search.css"/>
    <div id="layout-grid">
        <div id="search-grid">
            <form action="/offer/find">
                <div id="destination" class="search-input" style="display: inline-block;text-align: left">
                    <label id="destination-label" style="display: block" for="destination-input-field">Reiseziel</label>
                    <input id="destination-input-field" class="input-field" name="destination" type="text">
                </div>
                <div id="from-date" class="search-input" style="display: inline-block">
                    <label id="from-date-label" for="from-date-input-field"
                           style="display: block;text-align: left">Anreise</label>
                    <input id="from-date-input-field" class="input-field" name="dateStart" type="date">
                </div>
                <div id="to-date" class="search-input" style="display: inline-block">
                    <label id="to-date-label" for="to-date-label-input-field" style="display: block;text-align: left">Abreise</label>
                    <input id="to-date-label-input-field" class="input-field" name="dateEnd" type="date">
                </div>
                <div id="person-amount" class="search-input" style="display: inline-block">
                    <label id="person-amount-label" for="person-amount-input-field" style="display: block;text-align: left">Personen</label>
                    <input id="person-amount-input-field" class="input-field" name="persons" type="number">
                </div>
                <div style="margin: 10px">
                    <button class="btn-secondary">Ferienhaus suchen</button>
                </div>
            </form>
        </div>
        <div id="filter-grid">
            <h2>Filter</h2>
            <div id="tags" class="card">
                <h4>Tags</h4>
            </div>
            <div id="feature-bath" class="card">
                <h4>Badezimmer</h4>
            </div>
            <div id="feature-kitchen" class="card">
                <h4>Küche</h4>
            </div>
            <div id="feature-multimedia" class="card">
                <h4>Multimedia</h4>
            </div>
            <div id="feature-outdoor" class="card">
                <h4>Outdoor</h4>
            </div>
            <div id="feature-other" class="card">
                <h4>Sonstiges</h4>
            </div>
            <div id="feature-wellness" class="card">
                <h4>Wellness</h4>
            </div>
        </div>
        <div id="results">
            <h2>Ergebnisse</h2>
            <?php
            foreach ($houses as $house) {
                ?>
                <div class="card">
                    <?php print $house->getName() ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>




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
