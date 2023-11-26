<?php
namespace src\controller;

use src\models\User;

class RegisterController
{
    public function __construct()

    {
    }

    public function getform($formdata=null)
    {
        new ViewController('register');
    }
    public function postform($formdata=null)
    {
        $username=$_REQUEST['username'];
        $password=$_REQUEST['password'];
        $email=$_REQUEST['email'];

        $user=new User();
        $user->register($username,$password,$email);
    }


}