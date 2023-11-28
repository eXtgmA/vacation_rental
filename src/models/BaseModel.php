<?php

namespace src\models;
include_once("../src/config/database.php");

class BaseModel
{
    protected \mysqli|false $connection;

    protected function __construct()
    {
        session_start();
        $this->connection= \src\config\getConnection();
    }

}
