<?php
namespace src\controller;

class LogoutController
{
    public function __construct()

    {
    }

    public function postLogout($formdata=null)
    {
        session_start();
        unset($_SESSION['user']);
        header('location: /', true,302); // Redirect to landing page
    }

}