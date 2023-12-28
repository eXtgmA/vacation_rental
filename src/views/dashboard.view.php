<?php
$header = __DIR__ . "/partials/header.view.php";
$title = "Ferienhausvermietung";
$site = "dashboard";
include_once($header);
?>
<link rel="stylesheet" href="/styles/dashboard.css"/>
<div id="dashboard">
    <div id="search-area">
        <video playsinline muted autoplay id="background-video" poster="/assets/backyard.png">
            <source src="/assets/backyard.webm" type="video/webm">
            <source src="/assets/backyard.mp4" type="video/mp4">
        </video>
        <div id="title">
            <h1>Finde dein Traumhaus für<br/> den perfekten Urlaub</h1>
        </div>
        <form action="/offer/find" id="search-form">
            <div id="search">
                <div id="destination-search" class="search-input">
                    <label id="destination-label" for="destination-input-field">Reiseziel</label>
                    <input id="destination-input-field" class="input-field" name="destination" type="text">
                </div>
                <div id="from-date-search" class="search-input">
                    <label id="from-date-label" for="from-date-input-field">Anreise</label>
                    <input id="from-date-input-field" class="input-field" name="dateStart" type="date">
                </div>
                <div id="to-date-search" class="search-input">
                    <label id="to-date-label" for="to-date-input-field">Abreise</label>
                    <input id="to-date-input-field" class="input-field" name="dateEnd" type="date">
                </div>
                <div id="person-amount-search" class="search-input">
                    <label id="person-amount-label" for="person-amount-input-field">Personen</label>
                    <input id="person-amount-input-field" class="input-field" name="persons" type="number">
                </div>
            </div>
            <div id="search-button">
                <button class="btn-secondary">Ferienhaus suchen</button>
            </div>
        </form>
    </div>
    <div id="suggestions">
        <div id="suggestion-title">
            <h4>Entdecke auch mal was Neues</h4>
        </div>
        <div id="suggestion-cards">
            <div class="suggestion-card">
                <img class="card-image" src="/assets/haus1.jpg" alt="">
                <div class="card-content">
                    <span class="card-location">Berlin</span>
                    <span class="card-price">750 € / Nacht</span>
                </div>
                <span class="card-name">Haus auf grüner Wiese</span>
            </div>
            <div class="suggestion-card">
                <img class="card-image" src="/assets/haus2.jpg" alt="">
                <div class="card-content">
                    <span class="card-location">München</span>
                    <span class="card-price">100 € / Nacht</span>
                </div>
                <span class="card-name">Wohnung am Hang</span>
            </div>
            <div class="suggestion-card">
                <img class="card-image" src="/assets/haus3.jpg" alt="">
                <div class="card-content">
                    <span class="card-location">Hamburg</span>
                    <span class="card-price">50 € / Nacht</span>
                </div>
                <span class="card-name">Holzhaus umgeben von Baumaterial und vielem mehr</span>
            </div>
        </div>
    </div>
</div>
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>

<script src="/scripts/date-check.js"></script>
<script>
    if (document.getElementById('from-date-input-field').value === '') {
        document.getElementById('from-date-input-field').valueAsDate = new Date();
        dateStartElement.dispatchEvent(new Event('change'));
    }
</script>