<?php
$header = __DIR__ . "/partials/header.view.php";
$title = "Ferienhaussuche";
$site = "search";
$houses = $param['houses'] ?? [];
$houseCount = $param['houseCount'] ?? 0;

$features = $param['features'] ?? [];
$featuresSel = $param['featuresSelected'] ?? [];
$tagsSel = $param['tagsSelected'] ?? '';

include_once($header);
?>
<link rel="stylesheet" href="/styles/search.css"/>
<form action="/offer/find">
    <div id="search">
        <video playsinline muted autoplay id="background-video" poster="/assets/backyard.png">
            <source src="/assets/backyard.webm" type="video/webm">
            <source src="/assets/backyard.mp4" type="video/mp4">
        </video>
        <div id="search-card">
            <div id="destination" class="search-input" style="display: inline-block;text-align: left">
                <label id="destination-label" style="display: block" for="destination-input-field">Reiseziel</label>
                <input id="destination-input-field" placeholder="Reiseort eintragen" class="input-field" name="destination"
                       type="text" value="<?php echo $_SESSION['search-data']['destination'] ?? ''; ?>">
            </div>
            <div id="from-date" class="search-input" style="display: inline-block">
                <label id="from-date-label" for="from-date-input-field"
                       style="display: block;text-align: left">Anreise</label>
                <input id="from-date-input-field" class="input-field" name="dateStart" type="date"
                       value="<?php echo $_SESSION['search-data']['dateStart'] ?? ''; ?>">
            </div>
            <div id="to-date" class="search-input" style="display: inline-block">
                <label id="to-date-label" for="to-date-input-field" style="display: block;text-align: left">Abreise</label>
                <input id="to-date-input-field" class="input-field" name="dateEnd" type="date"
                       value="<?php echo $_SESSION['search-data']['dateEnd'] ?? ''; ?>">
            </div>
            <div id="person-amount" class="search-input" style="display: inline-block">
                <label id="person-amount-label" for="person-amount-input-field"
                       style="display: block; text-align: left">Personen</label>
                <input id="person-amount-input-field" placeholder="Zahl eintragen" class="input-field" name="persons" type="number"
                       value="<?php echo $_SESSION['search-data']['persons'] ?? '0'; ?>">
            </div>
            <div class="submit">
                <button class="btn-secondary" id="search-btn">Ferienhaus suchen</button>
            </div>
        </div>
    </div>
    <div id="layout">

        <div id="filter">
            <div class="headline">
                <div id="filter-fix" class="headline">
                    <h2>Filter</h2>
                </div>
                <div id="filter-open" class="headline">
                    <button type="button" title="Filter schließen" id="filter-close-btn">
                        <i class="fa-solid fa-chevron-up"></i>
                        <h2>Filter</h2>
                    </button>
                </div>
                <div id="filter-closed"  class="headline">
                    <button type="button" title="Filter öffnen" id="filter-open-btn">
                        <i class="fa-solid fa-chevron-down"></i>
                        <h2>Filter</h2>
                        <i class="fa-solid fa-filter" style="padding: 2px; font-size: 2rem;"></i>
                    </button>
                </div>
                <button type="button" class="" title="Filter löschen" onclick="clearFilter()" id="filter-reset-btn"><i class="fa-solid fa-filter-circle-xmark"></i></button>
            </div>
            <div class="collapse-content" id="filter-list">
                <div class="filter-list">
                    <div id="tags" class="filter-card">
                        <h3 style="margin-bottom: 0">Tags</h3>
                        <div id="tag-grid" onchange="filterResults()">
                            <div class="input-icon">
                                <i class="fa fa-magnifying-glass icon"></i>
                                <input class="input-field" type="text" placeholder="Tag-Namen">
                            </div>
                        </div>
                    </div>

                    <?php foreach ($features as $categoryName => $category) { ?>
                        <div class="filter-card">
                            <div class="feature-topic">
                                <h3><?php echo $categoryName; ?></h3>
                            </div>
                            <div class="feature-select-list">
                                <?php foreach ($category as $feature) { ?>
                                    <label class="feature-select">
                                        <input type="checkbox" onchange="useCheckbox()" class="feature-name"
                                               name="<?php echo 'features[' . $categoryName . '][]" value="' . $feature->getName(); ?>"
                                            <?php if (in_array($feature->getName(), array_values(($featuresSel[$categoryName] ?? [])))) {
                                                echo ' checked';
                                            } ?>>
                                        <?php echo $feature->getName(); ?>
                                    </label>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div id="results">
            <div class="headline">
                <h2>Ergebnisse <span class="result-stats">
                        (<span id="filter-result-count"><?php echo $houseCount ?? "0"; ?></span>/<?php echo $houseCount ?? "0"; ?>)</span>
                </h2>
            </div>
            <?php if (!empty($houses)) {
                foreach ($houses as $house) { ?>
                    <div class="result">
                        <div class="result-card" onclick="openLink('/offer/detail/<?php echo $house->getId() ?>')">
                            <div class="card-image">
                                <img src="<?php echo "/images/" . $house->getFrontimage(); ?>" alt="[alt]">
                            </div>
                            <div class="information">
                                <div class="sub-headline">
                                    <h3 id="name"><?php print $house->getName() ?></h3>
                                </div>
                                <div class="details">
                                    <div class="price detail">
                                        <div class="text">
                                            <span class="key">Preis</span>
                                            <span class="value"><?php print $house->getPrice() ?>€</span>
                                        </div>
                                        <hr/>
                                    </div>
                                    <div class="max-person detail">
                                        <div class="text">
                                            <span class="key">Max. Personen</span>
                                            <span class="value"><?php print $house->getMaxPerson() ?></span>
                                        </div>
                                        <hr/>
                                    </div>
                                    <div class="square-meter detail">
                                        <div class="text">
                                            <span class="key">Fläche</span>
                                            <span class="value"><?php print $house->getSquareMeter() ?>m²</span>
                                        </div>
                                        <hr class="horizontal-line"/>
                                    </div>
                                    <div class="room-count detail">
                                        <div class="text">
                                            <span class="key">Räume</span>
                                            <span class="value"><?php print $house->getRoomCount() ?></span>
                                        </div>
                                        <hr/>
                                    </div>
                                    <div class="city detail">
                                        <div class="text">
                                            <span class="key">Stadt</span>
                                            <span class="value"><?php print $house->getPostalCode() . ' ' . $house->getCity() ?></span>
                                        </div>
                                        <hr/>
                                    </div>
                                    <div class="street detail">
                                        <div class="text">
                                            <span class="key">Straße</span>
                                            <span class="value"><?php print $house->getStreet() . ' ' . $house->getHouseNumber() ?></span>
                                        </div>
                                        <hr/>
                                    </div>
                                    <div class="submit">
                                        <button class="btn-primary" type="button"
                                                onclick="openLink('/offer/detail/<?php echo $house->getId() ?>')">
                                            Ansehen
                                        </button>
                                    </div>
                                    <div class="tags" style="display: none">
                                        <?php echo $house->getAllTagsString() ?>
                                    </div>
                                    <div class="features" style="display: none">
                                        <?php echo $house->getAllFeaturesString() ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } else {
                echo "<span>Es tut uns leid, aber zu Ihrer Auswahl wurden keine Ferienhäuser gefunden.</span>";
            } ?>
        </div>
    </div>
</form>
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>

<script src="/scripts/search.js"></script>
<script src="/scripts/date-check.js"></script>

