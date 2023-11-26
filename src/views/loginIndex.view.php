<?php
session_start();
array_key_exists('message',$_SESSION)?$message=$_SESSION['message']:$message=null;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>Login Page</h1>
<div style="display: flex;justify-content: center">
    <h3>Login</h3>
    <?php
    echo ($message ?? "<h1>$message</h1>");
    session_unset();
    ?>

</div>
<div style="display: flex;justify-content: center">
<form action="/login" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" required>
        <br>
        <br>
        <label for="password">Password</label>
        <input type="password" name="password" required>
    <br>
    <br>
        <button type="submit">Login</button>
    </form>
</div>
<div style="display: flex;justify-content: center">
<h3>Noch keinen Account ?
    <form action="">
        <button type="submit">Account Anlegen</button>
    </form>
</h3>
</div>
</body>
</html>
