<?php
namespace src\controller;
use src\models\User;

class loginController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return the login Mask
     *
     * @param $formdata
     * @return void
     */
    public function getLogin($formdata=null): void
    {
        new ViewController('loginIndex');
    }

    public function postLogin(): void
    {
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        $user=new User();
        $user->login($username,$password);
    }

}
