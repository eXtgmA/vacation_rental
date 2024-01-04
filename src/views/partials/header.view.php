<?php
$title = $title ?? "no title";

?>
<!doctype html>
<html lang="de">
<head>
    <link rel="icon" href="/assets/logo.png" type="image/png">

    <link rel="stylesheet" href="/styling.css"/>
    <link rel="stylesheet" href="/fa/css/all.min.css" type="text/css" />
    <script src="/scripts.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#478386">
    <title><?php echo $title;?></title>
</head>
<body>
<script src="/scripts/notification.js"></script>
<?php
// display an error message
if (array_key_exists('message', $_SESSION)) { ?>
    <script>
        showNotification("<?php echo $_SESSION['message']; ?>");
    </script>
<?php }

if (isset($_SESSION['user'])) {
    include_once('navbar.view.php');
} else {
    include_once('navbar-blank.view.php');
}
// helper functions for frontend:
include_once("../src/helper/prefill.php");
?>
