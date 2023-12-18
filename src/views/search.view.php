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

/**
 * @param string $string
 * @return void
 */
function pold($string): void
{
    $old = $_SESSION['old_POST'] ?? null;
    if (isset($old)) {
        print $old[$string] ?? '';
    }
}

$features = $param['features'] ?? null;

include_once($header);
?>
<link rel="stylesheet" href="/styles/search.css"/>
<form action="/offer/find">
    <div id="layout-grid">
        <div id="search-grid">
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
                <label id="person-amount-label" for="person-amount-input-field" style="display: block; text-align: left">Personen</label>
                <input id="person-amount-input-field" class="input-field" name="persons" type="number">
            </div>
            <div class="submit">
                <button class="btn-secondary"><span class="optional-search-text">Ferienhaus</span> suchen</button>
            </div>
        </div>
        <div id="filter">
            <div class="filter-list">
                <div class="headline">
                    <h2>Filter</h2>
                </div>
                <div id="tags" class="card">
                    <h3 style="margin-bottom: 0px">Tags</h3>
                    <div id="tag-grid">
                        <div class="input-icon">
                            <i class="fa fa-magnifying-glass icon"></i>
                            <input class="input-field" type="text" placeholder="Tag-Namen">
                        </div>
                    </div>
                </div>

                <?php foreach ($features as $categoryName => $category) { ?>
                    <div class="card">
                        <div class="feature-topic">
                            <h3><?php echo $categoryName; ?></h3>
                        </div>
                        <div class="feature-select-list">
                            <?php foreach ($category as $feature) { ?>
                                <label class="feature-select">
                                    <input type="checkbox" name="<?php echo 'features[' . $categoryName . '][]" value="' . $feature->getName(); ?>">
                                    <?php echo $feature->getName(); ?>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div id="results">
            <div class="headline">
                <h2>Ergebnisse</h2>
            </div>
            <?php foreach ($houses as $house) { ?>
                <div class="result">
                    <div class="result-card">
                        <div class="card-image">
                            <img src="<?php echo "/images/" . $house->getFrontimage(); ?>" alt="[alt]">
                        </div>
                        <div class="information">
                            <div class="sub-headline">
                                <h3><?php print $house->getName() ?></h3>
                            </div>
                            <div class="details">
                                <div class="price detail">
                                    <div class="text">
                                        <span>Preis</span>
                                        <span><?php print $house->getPrice() ?></span>
                                    </div>
                                    <hr/>
                                </div>
                                <div class="max-person detail">
                                    <div class="text">
                                        <span>Max. Personen</span>
                                        <span><?php print $house->getMaxPerson() ?></span>
                                    </div>
                                    <hr/>
                                </div>
                                <div class="square-meter detail">
                                    <div class="text">
                                        <span>Fläche</span>
                                        <span><?php print $house->getSquareMeter() ?></span>
                                    </div>
                                    <hr class="horizontal-line"/>
                                </div>
                                <div class="room-count detail">
                                    <div class="text">
                                        <span>Räume</span>
                                        <span><?php print $house->getRoomCount() ?></span>
                                    </div>
                                    <hr/>
                                </div>
                                <div class="city detail">
                                    <div class="text">
                                        <span>Stadt</span>
                                        <span><?php print $house->getPostalCode() . ' ' . $house->getCity() ?></span>
                                    </div>
                                    <hr/>
                                </div>
                                <div class="street detail">
                                    <div class="text">
                                        <span>Straße</span>
                                        <span><?php print $house->getStreet() . ' ' . $house->getHouseNumber() ?></span>
                                    </div>
                                    <hr/>
                                </div>
                                <div class="submit">
                                    <button class="btn-primary" type="button">Ansehen</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</form>


<div id="dashboard-grid">
    <div id="title">
        <h1>Finde dein Traumhaus für den perfekten Urlaub</h1>
    </div>
    <div id="search">
        <form action="/offer/find">
            <div id="destination-search" class="search-input" style="display: inline-block;text-align: left">
                <label id="destination-label" style="display: block" for="destination-input-field">Reiseziel</label>
                <input id="destination-input-field" class="input-field" name="destination" type="text" value="<?php pold('destination') ?>">
            </div>
            <div id="from-date-search" class="search-input" style="display: inline-block">
                <label id="from-date-label" for="from-date-input-field"
                       style="display: block;text-align: left">Anreise</label>
                <input id="from-date-input-field" class="input-field" name="dateStart" type="date" value="<?php pold('dateStart') ?>">
            </div>
            <div id="to-date-search" class="search-input" style="display: inline-block">
                <label id="to-date-label" for="to-date-label-input-field" style="display: block;text-align: left">Abreise</label>
                <input id="to-date-label-input-field" class="input-field" name="dateEnd" type="date" value="<?php pold('dateEnd') ?>">
            </div>
            <div id="person-amount-search" class="search-input" style="display: inline-block">
                <label id="person-amount-label" for="person-amount-input-field" style="display: block;text-align: left">Personen</label>
                <input id="person-amount-input-field" class="input-field" name="persons" type="number" value="<?php pold('persons') ?>">
            </div>
            <div style="margin: 10px">
                <button class="btn-secondary">Ferienhaus suchen</button>
            </div>
        </form>
    </div>
</div>
<?php
if ($houses) {
    foreach ($houses as $house) {
        ?>
        <div class="card">
            <?php print $house->getName() ?>
            <?php print $house->getCity() ?>
            <a href="/booking/create/<?php echo $house->getId() ?>">Buch mich</a>
        </div>
        <?php
    }
}

?>

<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>

<script src="/scripts/search.js"></script>
