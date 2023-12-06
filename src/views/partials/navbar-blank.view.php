<?php
$site = explode('/', $_SERVER['REQUEST_URI'])[1];
$site = empty($site) ? "dashboard" : $site; // avoid empty value on redirect
?>
<div class="navparent">
    <img src="/images/unbenannt.png" alt="logo">
    <cont style="width: 78%;display: inline-block;text-align: right">
        <a class="<?php echo $site=='dashboard'? 'active':'';?>" href="/dashboard"><i class="fa fa-magnifying-glass"></i></a>
        <a class="<?php echo $site=='register'? 'active':'';?>" href="/register"><i class="fa fa-user-plus"></i></a>
        <a class="<?php echo $site=='login'? 'active':'';?>" href="/login"><i class="fa fa-key"></i></a>
    </cont>
</div>
