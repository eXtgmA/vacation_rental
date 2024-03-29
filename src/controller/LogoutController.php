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
        $_SESSION['filter'] = ['tags'=>[], 'features'=>[]];
        session_unset();
        redirect("/dashboard", 302);
    }
}
