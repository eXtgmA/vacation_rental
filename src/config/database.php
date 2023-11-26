<?php
namespace src\config;

function getConnection(){
$connection=mysqli_connect('localhost', 'shop', '1234', 'shop');
return $connection;
}
