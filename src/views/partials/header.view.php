<?php
global $title, $page;
if(!isset($_SESSION)) // avoid double opening sessions
{
    session_start();
}
// create the variable $message if there are existing messages
array_key_exists('message',$_SESSION)?$message=$_SESSION['message']:$message=null;
?>
<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/styling.css"
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $title;?></title>
</head>
<body>
<?php
if ($page!="login" && $page!= 'register' && $page!="home"){ // avoid showing header on entry point pages
    if(isset($_SESSION['user'])){
        include_once('navbar.view.php');
    }
}
?>
