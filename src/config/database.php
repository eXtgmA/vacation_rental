<?php
namespace src\config;

use mysqli;

/**
 * create connection to database
 *
 * @return mysqli
 */
function getConnection(): mysqli{
    return mysqli_connect('localhost', 'shop', '1234', 'shop');
}
