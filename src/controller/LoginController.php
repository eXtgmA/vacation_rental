<?php
namespace src\controller;
use src\models\User;

class loginController extends BaseController
{
    public function __construct()
    {
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