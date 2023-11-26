<?php
namespace src\controller;
use src\models\User;

class loginController
{
    public function __construct()
    {
        session_start();
        if (array_key_exists('user',$_SESSION)){
            // user is logged in , so redirect to dashboard
            header('location: /dashboard', true ,302);
        }
    }

    /**
     * Return the login Mask
     *
     * @param $formdata
     * @return void
     */
    public function getLogin($formdata=null)
    {
        new ViewController('loginIndex');
    }

    public function postLogin()
    {
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        $user=new User();
        $user->login($username,$password);
    }

}