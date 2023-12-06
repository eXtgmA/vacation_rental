<?php
$site = explode('/', $_SERVER['REQUEST_URI'])[1];
?>
<div class="navparent">
    <img src="/images/unbenannt.png" alt="logo">
    <cont style="width: 78%;display: inline-block;text-align: right">
        <a class="<?php echo $site=='dashboard'? 'active':'';?>" href="/dashboard"><i class="fa fa-magnifying-glass"></i></a>
        <a class="<?php echo $site=='cart'? 'active':'';?>" href="/cart"><i class="fa fa-cart-shopping"></i></a>
        <a class="<?php echo $site=='profile'? 'active':'';?>" href=""><i class="fa fa-user"></i></a>
        <a class="<?php echo $site=='offer'? 'active':'';?>" href="/offer"><i class="fa fa-hand-holding-dollar"></i></a>
        <form action="/logout" method="post" style="display: inline">
            <button type="submit" >
          <a type="submit"  class="<?php echo $site=='logout'? 'active':'';?>"><i class="fa fa-right-from-bracket"></i></a></button>
        </form>
    </cont>
</div>
