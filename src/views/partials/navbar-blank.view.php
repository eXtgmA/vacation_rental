<?php
$siteString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$site = substr((string)$siteString, 1);
?>
<link rel="stylesheet" href="/styles/navbar.css"/>
<div class="navbar">
    <div class="logo">
        <img src="/assets/logo.png" onclick="openLink('/')" alt="logo">
    </div>
    <div class="navlinks">
        <a class="<?php echo in_array($site, ['', 'dashboard'])? 'active':'';?>" href="/dashboard" title="Startseite"><i class="fa-solid fa-house"></i></a>
        <a class="<?php echo ($site=='offer/find' || str_starts_with($site, 'offer/detail') )? 'active' : '' ?>" href="/offer/find" title="Suchen"><i class="fa fa-magnifying-glass"></i></a>
        <a class="<?php echo $site=='register'? 'active':'';?>" href="/register" title="Registrieren"><i class="fa fa-user-plus"></i></a>
        <a class="<?php echo $site=='login'? 'active':'';?>" href="/login" title="Anmelden"><i class="fa fa-key"></i></a>
    </div>
    <div class="hamburger-menu">
        <i class="fa fa-bars"></i>
        <div class="hamburger-navlinks">
            <a class="<?php echo in_array($site, ['', 'dashboard'])? 'active':'';?>" href="/dashboard"><i class="fa-solid fa-house"></i> Startseite</a>
            <a class="<?php echo ($site=='offer/find' || str_starts_with($site, 'offer/detail') )? 'active':'';?>" href="/offer/find"><i class="fa fa-magnifying-glass"></i> Suche</a>
            <a class="<?php echo $site=='register'? 'active':'';?>" href="/register"><i class="fa fa-user-plus"></i> Registrieren</a>
            <a class="<?php echo $site=='login'? 'active':'';?>" href="/login"><i class="fa fa-key"></i> Login</a>
        </div>

    </div>
</div>
<script src="/scripts/navbar.js"></script>