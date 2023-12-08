<?php
$site = explode('/', $_SERVER['REQUEST_URI'])[1];
$site = empty($site) ? "dashboard" : $site; // avoid empty value on redirect
?>
<link rel="stylesheet" href="/styles/navbar.css"/>
<div class="navparent">
    <div class="logo">
        <img src="/assets/logo.png" alt="logo">
    </div>
    <div class="navlinks">
        <a class="<?php echo $site=='dashboard'? 'active':'';?>" href="/dashboard" title="Suchen"><i class="fa fa-magnifying-glass"></i></a>
        <a class="<?php echo $site=='register'? 'active':'';?>" href="/register" title="Registrieren"><i class="fa fa-user-plus"></i></a>
        <a class="<?php echo $site=='login'? 'active':'';?>" href="/login" title="Anmelden"><i class="fa fa-key"></i></a>
    </div>
    <div class="sandwich-menu">
        <i class="fa fa-bars"></i>
        <div class="sandwich-navlinks">
            <a class="<?php echo $site=='dashboard'? 'active':'';?>" href="/dashboard"><i class="fa fa-magnifying-glass"></i> Suche</a>
            <a class="<?php echo $site=='register'? 'active':'';?>" href="/register"><i class="fa fa-user-plus"></i> Registrieren</a>
            <a class="<?php echo $site=='login'? 'active':'';?>" href="/login"><i class="fa fa-key"></i> Login</a>
        </div>

    </div>
</div>
<script src="/scripts/navbar.js"></script>