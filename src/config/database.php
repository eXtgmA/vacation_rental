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
function getConnection(): mysqli
{
    // get database credentials
    $hostname = getenv('DB_HOST') ?: 'localhost';
    $username = getenv('DB_USER') ?: 'shop';
    $password = getenv('DB_PASSWORD') ?: '1234';
    $database = getenv('DB_NAME') ?: 'vacation_rental_db';
    $port = (int)getenv('DB_PORT') ?: 3306;

    // create connection
    $connection = mysqli_connect($hostname, $username, $password, $database, $port);

    // check if connection was successful
    if ($connection === false) {
        throw new Exception('Failed to connect to the database.');
    }
    $connection->set_charset("utf8mb4");
    return $connection;
}
