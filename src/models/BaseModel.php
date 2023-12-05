<?php

namespace src\models;

include_once("../src/config/database.php");
include_once("../src/helper/redirect.php");

class BaseModel
{
    protected \mysqli $connection;

    protected function __construct()
    {
        $this->connection= \src\config\getConnection();
    }
}
