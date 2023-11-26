<?php
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
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $title;?></title>
</head>
<body>
