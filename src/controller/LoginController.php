<?php
namespace src\controller;

use src\models\User;

class LoginController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return the login Mask
     *
     * @param mixed $formdata
     * @return void
     */
    public function getLogin(mixed $formdata = null): void
    {
        new ViewController('loginIndex');
    }

    public function postLogin(): void
    {
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
        $user=new User();
        $user->login($email, $password);
    }

}
