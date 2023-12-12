<?php
$header = __DIR__ . "/partials/header.view.php";
$title = "dashboard";
$site = "dashboard";
include_once($header);
?>
    <link rel="stylesheet" href="/styles/dashboard.css"/>
    <div id="dashboard-grid">
        <div id="title">
            <h1>Finde dein Traumhaus für den perfekten Urlaub</h1>
        </div>
        <div id="search">
            <div id="destination-search" class="search-input" style="display: inline-block;text-align: left">
                <label id="destination-label" style="display: block" for="destination-input-field">Reiseziel</label>
                <input id="destination-input-field" class="input-field" name="Reiseziel" type="text">
            </div>
            <div id="from-date-search" class="search-input" style="display: inline-block">
                <label id="from-date-label" for="from-date-input-field"
                       style="display: block;text-align: left">Anreise</label>
                <input id="from-date-input-field" class="input-field" name="Anreise" type="date">
            </div>
            <div id="to-date-search" class="search-input" style="display: inline-block">
                <label id="to-date-label" for="to-date-label-input-field" style="display: block;text-align: left">Abreise</label>
                <input id="to-date-label-input-field" class="input-field" name="Abreise" type="date">
            </div>
            <div id="person-amount-search" class="search-input" style="display: inline-block">
                <label id="person-amount-label" for="person-amount-input-field" style="display: block;text-align: left">Personen</label>
                <input id="person-amount-input-field" class="input-field" name="Personen" type="number">
            </div>
            <div style="margin: 10px">
                <button class="btn-secondary">Ferienhaus suchen</button>
            </div>
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
                    <span class="card-name">Holzhaus umgeben von Baumaterial</span>
                </div>
            </div>
        </div>
    </div>
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer)
?>
