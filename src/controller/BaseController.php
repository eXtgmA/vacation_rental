<?php
namespace src\controller;

include_once("../src/config/database.php");

class BaseController
{
    protected \mysqli $connection;
    public function __construct()
    {
        $this->connection= \src\config\getConnection();
    }

    protected function redirectIfNotLoggedIn(): void
    {
        if (!isset($_SESSION['user'])) {
            header('location: /login', true, 302);
        }
    }
}
