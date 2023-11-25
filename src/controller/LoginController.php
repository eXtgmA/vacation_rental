<?php
namespace src\controller;


use src\models\User;

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
        $_REQUEST;
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];

        $user=new User();
        $user->login($username,$password);
    }

}