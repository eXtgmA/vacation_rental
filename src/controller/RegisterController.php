<?php
namespace src\controller;

use src\models\User;

class RegisterController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getform(mixed $formdata=null): void
    {
        new ViewController('register');
    }
    public function postform(mixed $formdata=null): void
    {
        $username=$_REQUEST['username'];
        $password=$_REQUEST['password'];
        $email=$_REQUEST['email'];

        $user=new User();
        $user->register($username,$password,$email);
    }


}
