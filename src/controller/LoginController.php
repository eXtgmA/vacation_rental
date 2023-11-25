<?php
namespace src\controller;

/*
 * This class will manage the login logic
 */
class loginController
{
    public function __construct()

    {
    }

    public function getLogin($formdata=null)
    {
        // Check if there is a user with the send login mail or username
        // if the user exist check if the send password is correct
        // if everything is ok perform login and set user as active user for the session
        new ViewController('loginIndex');
    }

    public function postLogin()
    {
        var_dump('login will be processed');
    }

}