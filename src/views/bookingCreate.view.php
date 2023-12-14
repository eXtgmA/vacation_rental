<?php
$header = __DIR__ . "/partials/header.view.php";
$title = "Haus buchen";
$house = $param["house"] ?? null;
$options = $param["options"] ?? null;
$bookedDays = $param["bookedDays"] ?? null;
include_once($header);
?>
<?php
//echo($message ?? "<h1>$message</h1>");
?>
    <link rel="stylesheet" href="/styles/booking-create.css"/>
    <script src="/scripts/calendar-widget.js"></script>

    <img id="title-image" src="/images/<?php print $house->getFrontImage(); ?>" style="" alt="alt">

    <h1 class="title"><?php print $house->getName(); ?> buchen</h1>
    <form action="/booking/create/" method="post" id="booking-form">

        <div class="description-area">
            <p><?php print $house->getDescription(); ?></p>
        </div>
        <div class="price-area">
            <p>Preis pro Nacht: <?php print $house->getPrice(); ?>€</p>
        </div>
        <div class="occupancy-area">
            <h3>Buchungsauslastung</h3>
            <div class="occupancy-calendar">
                <div id="calendar-occupancy" class="calendar"></div>
                <script>
                    drawCalendar("calendar-occupancy", currentMonth, currentYear, bookedDays);
                </script>
            </div>
        </div>
        <div class="booking-period-area">
            <h3>Buchungszeitraum</h3>
            <label class="label" for="date_start">Von</label>
            <input class="input-field" type="date" id="date_start" name="date_start" value="<?php prefill('date_end') ?>"
                   required>
            <label class="label" for="date_end">Bis</label>
            <input class="input-field" type="date" id="date_end" name="date_end" value="<?php prefill('date_end') ?>"
                   required>
        </div>
        <div class="options-area">
            <h3>Zusatzoptionen</h3>
            <div class="options-list">
                <label class="option">
                    <input type="checkbox">
                    Pool
                </label>
                <label class="option">
                    <input type="checkbox">
                    Garage
                </label>
                <label class="option">
                    <input type="checkbox">
                    Room Service
                </label>
                <label class="option">
                    <input type="checkbox">
                    TV
                </label>
            </div>
            <?php print_r($options) ?> <!-- todo checkboxes for options -->
        </div>
        <div class="cost-summary-area">
            <h3>Gesamtpreis</h3>
            <div class="cost-summary">
                <div class="card cost-value">
                    <span>300€</span>
                    <?php prefill('price') ?> <!-- todo JS (Marvin) add live calculation for Gesamtpreis -->
                </div>
            </div>
        </div>
        <div class="submit-area">
            <input type="hidden" id="house_id" name="house_id" value=<?php echo $house->getId(); ?>>
            <button type="submit" class="btn-primary">Zum Warenkorb hinzufügen</button>
        </div>
    </form>
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>