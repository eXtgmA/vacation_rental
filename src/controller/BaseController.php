<?php
namespace src\controller;

use src\helper\DatabaseTrait;
use src\helper\ValidationTrait;

include_once("../src/helper/redirect.php");
include_once("../src/config/database.php");
class BaseController
{
    use ValidationTrait,DatabaseTrait;
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
