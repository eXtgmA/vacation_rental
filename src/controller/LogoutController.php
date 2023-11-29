<?php
namespace src\controller;

class LogoutController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function postLogout(mixed $formdata = null): void
    {
        session_start();
        unset($_SESSION['user']);
        header('location: /', true, 302); // Redirect to landing page
    }
}
