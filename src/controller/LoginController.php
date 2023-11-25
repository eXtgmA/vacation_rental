<?php
/*
 * This class will manage the login logic
 */

namespace src\controller;

class loginController
{

    public function __construct()
    {
    }

    public function index($formdata=null)
    {
        // Check if there is a user with the send login mail or username
        // if the user exist check if the send password is correct
        // if everything is ok perform login and set user as active user for the session
        new ViewController('loginIndex');
    }


}