<?php
namespace src\controller;

use src\helper\DatabaseTrait;

include_once("../src/config/database.php");

class BaseController
{
    use DatabaseTrait;
    public function __construct()
    {
    }

    protected function redirectIfNotLoggedIn(): void
    {
        if (!isset($_SESSION['user'])) {
            header('location: /login', true, 302);
        }
    }
}
