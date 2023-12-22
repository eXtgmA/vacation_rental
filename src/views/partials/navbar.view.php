<?php
$siteString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$site = substr((string)$siteString, 1);
?>
<link rel="stylesheet" href="/styles/navbar.css"/>
<div class="navparent">
    <div class="logo">
        <img src="/assets/logo.png" onclick="openLink('/')" alt="logo">
    </div>
    <div class="navlinks">
        <a class="<?php echo in_array($site, ['', 'dashboard', 'offer/find']) ? 'active' : '' ?>" href="/dashboard" title="Suchen"><i class="fa fa-magnifying-glass"></i></a>
        <a class="<?php echo in_array($site, ['cart', 'checkout']) ? 'active' : ''; ?>" href="/cart" title="Warenkorb"><i class="fa fa-cart-shopping"></i></a>
        <a class="<?php echo $site == 'profile' ? 'active' : ''; ?>" href="/profile" title="Benutzer verwalten"><i class="fa fa-user"></i></a>
        <a class="<?php echo  (str_contains($site, 'offer') && !str_contains($site, 'find')) ? 'active' : ''; ?>" href="/offer" title="Haus vermieten"><i class="fa fa-hand-holding-dollar"></i></a>
        <form action="/logout" method="post">
            <button type="submit"><i class="fa fa-right-from-bracket"></i></button>
        </form>
    </div>
    <div class="hamburger-menu">
        <i class="fa fa-bars"></i>
        <div class="hamburger-navlinks">
            <a class="<?php echo in_array($site, ['', 'dashboard', 'offer/find']) ? 'active' : ''; ?>" href="/dashboard" title="Suchen"><i class="fa fa-magnifying-glass"></i> Suchen</a>
            <a class="<?php echo in_array($site, ['cart', 'checkout']) ? 'active' : ''; ?>" href="/cart" title="Warenkorb"><i class="fa fa-cart-shopping"></i> Warenkorb</a>
            <a class="<?php echo $site == 'profile' ? 'active' : ''; ?>" href="" title="Benutzer verwalten"><i class="fa fa-user"></i> Benutzer verwalten</a>
            <a class="<?php echo  (str_contains($site, 'offer') && !str_contains($site, 'find'))  ? 'active' : '' ?>" href="/offer" title="Haus vermieten"><i class="fa fa-hand-holding-dollar"></i> Haus vermieten</a>
            <form action="/logout" method="post" style="display: flex; justify-content: center;">
                <button type="submit" style="font-size: 24px"><i class="fa fa-right-from-bracket"></i> Logout</button>
            </form>
        </div>
    </div>
</div>
<script src="/scripts/navbar.js"></script>
