<?php
$header = __DIR__ . "/partials/header.view.php";
$title = "Meine Häuser";
$page = 'offerindex';
$_SESSION['previous'] = '/offer';
$houses = $param['houses'] ?? [];
include_once($header);
?>
<link rel="stylesheet" href="/styles/offer.css"/>
<script src="/scripts/calendar-widget.js"></script>
<div class="content-layout">

    <div class="page-header">
        <h1 style="">Eigene Häuser verwalten</h1>
        <button class="btn-primary" onclick="openLink('/offer/create')">Neues Haus anlegen</button>
    </div>
    <?php
    if (isset($houses)) {
        foreach ($houses as $house) {
            ?>
            <div class="offer-card<?php !$house->getIsDisabled() ?: print ' disabled' ?>">
                <div class="card-image">
                    <img src="/images/<?php print $house->getFrontImage() ?>" alt="Frontansicht des Ferienhauses">
                </div>
                <div class="card-details">
                    <h2 class="card-title"><?php print $house->getName() ?></h2>
                    <hr style="width: 80%"/>
                    <p><?php print $house->getStreet() . " " . $house->getHouseNumber() . ", " . $house->getPostalCode() . " " . $house->getCity() ?></p>
                    <div class="button-container">
                        <button class="btn-primary" onclick="openLink('/offer/edit/<?php echo $house->getId() ?>')">
                            Bearbeiten
                        </button>
                        <button type="submit" class="btn-secondary" onclick="sendPostRequest('<?php echo "/offer/togglestatus/" . $house->getId(); ?>')">
                            <?php $house->getIsDisabled() == 1 ? print('Aktivieren') : print('Deaktivieren') ?>
                        </button>
                        <?php if ($house->getBookedDates() == "") { ?>
                            <!-- deleting a house is only possible if no bookings exist for that house -->
                            <form action="/offer/delete/<?php echo $house->getId(); ?>" method="post">
                                <button type="submit" class="btn-secondary">
                                    Löschen
                                </button>
                            </form>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-calendars">
                    <div id="calendar-<?php print $house->getId() ?>" class="calendar">
                        <script>
                            drawCalendar("calendar-<?php echo $house->getId() ?>", currentMonth, currentYear, <?php echo $param['bookedDays'][$house->getID()] ?? '""'; ?>);
                        </script>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>
