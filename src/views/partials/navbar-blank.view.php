<?php
$siteString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$site = substr((string)$siteString, 1);
?>
<link rel="stylesheet" href="/styles/navbar.css"/>
<div class="navparent">
    <div class="logo">
        <img src="/assets/logo.png" alt="logo">
    </div>
    <div class="navlinks">
        <a class="<?php echo in_array($site, ['', 'dashboard', 'offer/find']) ? 'active' : '' ?>" href="/dashboard" title="Suchen"><i class="fa fa-magnifying-glass"></i></a>
        <a class="<?php echo $site=='register'? 'active':'';?>" href="/register" title="Registrieren"><i class="fa fa-user-plus"></i></a>
        <a class="<?php echo $site=='login'? 'active':'';?>" href="/login" title="Anmelden"><i class="fa fa-key"></i></a>
    </div>
    <div class="hamburger-menu">
        <i class="fa fa-bars"></i>
        <div class="hamburger-navlinks">
            <a class="<?php echo in_array($site, ['', 'dashboard', 'offer/find']) ? 'active':'';?>" href="/dashboard"><i class="fa fa-magnifying-glass"></i> Suche</a>
            <a class="<?php echo $site=='register'? 'active':'';?>" href="/register"><i class="fa fa-user-plus"></i> Registrieren</a>
            <a class="<?php echo $site=='login'? 'active':'';?>" href="/login"><i class="fa fa-key"></i> Login</a>
        </div>

    </div>
</div>
<script src="/scripts/navbar.js"></script>