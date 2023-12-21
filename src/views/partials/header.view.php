<?php
$title = $title ?? "no title";

if (!isset($_SESSION)) { // avoid double opening sessions
   // session_start();
}
// create the variable $message if there are existing messages
array_key_exists('message', $_SESSION)?$message=$_SESSION['message']:$message=null;

// auto redirect to login page, if the user tries to access a page that requires login
if (!isset($_SESSION['user']) && !preg_match('#^/(login|register|dashboard|impressum|(offer/(find|(detail/\d*))))$#', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '')) {
    $_SESSION['redirect_back'] = $_SERVER['REQUEST_URI'];
    header('location: /login', true, 302);
}

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
    <title><?php echo $title;?></title>
</head>
<body>
<script src="/scripts/notification.js"></script>
<?php
if (isset($_SESSION['user'])) {
    include_once('navbar.view.php');
} else {
    include_once('navbar-blank.view.php');
}
// helper functions for frontend:
include_once("../src/helper/prefill.php");
?>
