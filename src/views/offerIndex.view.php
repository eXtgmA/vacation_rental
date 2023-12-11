<?php
$header = __DIR__ . "/partials/header.view.php";
$title = "Meine Häuser";
$page = 'offerindex';
include_once($header);
?>
    <link rel="stylesheet" href="/styles/offer.css"/>
    <script src="/scripts/calendar-widget.js"></script>

<div class="page-header">
    <h1 style="">Eigene Häuser verwalten</h1>
    <button class="btn-primary" onclick="openLink('/offer/create')">Neues Haus anlegen</button>
</div>
<?php
if (isset($param)) {
    foreach ($param as $house) {
        ?>
        <div class="offer-card">
            <div class="card-image">
                <img src="/images/<?php print $house->getFrontImage() ?>" alt="alt">
            </div>
            <div class="card-details">
                <h2 class="card-title"><?php print $house->getName() ?></h2>
                <hr style="width: 80%"/>
                <p><?php print $house->getStreet() . " " . $house->getHouseNumber() . ", " . $house->getPostalCode() . " " . $house->getCity() ?></p>
                <div class="button-container">
                    <button class="btn-secondary" onclick="openLink('/offer/show/<?php echo $house->getId() ?>')">
                        Bearbeiten
                    </button>
                    <!--                    <button class="btn-primary">Deaktivieren</button>-->
                    <form action="/offer/togglestatus/<?php echo $house->getId(); ?>" method="post">
                        <button type="submit" class="btn-primary">
                            <?php $house->getIsDisabled() == 1 ? print('Aktivieren') : print('Deaktivieren') ?>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-calendars">
                <div id="calendar-<?php print $house->getId() ?>" class="calendar">
                    <script>
                        drawCalendar("calendar-<?php echo $house->getId() ?>", currentMonth, currentYear, bookedDays);
                    </script>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>