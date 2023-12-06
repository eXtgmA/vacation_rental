<?php
namespace src\controller;

include_once("../src/helper/redirect.php");
include_once("../src/config/database.php");
class BaseController
{
    public function __construct()
    {
    }

    protected function redirectIfNotLoggedIn(): void
    {
        if (!isset($_SESSION['user'])) {
            header('location: /dashboard', true, 302);
        }
    }
}
