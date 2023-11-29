<?php
namespace src\config;

use Exception;
use mysqli;

/**
 * create connection to database
 *
 * @return mysqli
 * @throws Exception
 */
function getConnection(): mysqli{
    $connection = mysqli_connect('localhost', 'shop', '1234', 'shop');

    // check if connection was successful
    if ($connection === false) {
        throw new Exception('Failed to connect to the database.');
    }

    return $connection;
}
