<?php
$header=__DIR__."\\partials\\header.view.php";
$title = "Dashboard";
$site = 'dashboard';
include_once($header);
?>

<div>
    Hallo hier ist das Dashboard
    <form action="/logout" method="post">
        <button type="submit">Logout</button>
    </form>
</div>

<?php
$footer=__DIR__."\\partials\\footer.view.php";
include_once($footer)
?>