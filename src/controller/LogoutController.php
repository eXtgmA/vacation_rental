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
        unset($_SESSION['user']);
        session_unset();
        header('location: /', true, 302); // Redirect to landing page
    }
}
