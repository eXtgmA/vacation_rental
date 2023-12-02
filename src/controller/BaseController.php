<?php
namespace src\controller;

class BaseController
{
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
