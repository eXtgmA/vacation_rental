<?php
namespace src\controller;

use src\models\User;

class RegisterController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getform(mixed $formdata = null): void
    {
        new ViewController('register');
    }
    public function postform(mixed $formdata = null): void
    {
        $input = $_REQUEST;
        $user=new User($input);
        $userExists=$user->checkIfExist();
        if($userExists){
            $_SESSION['message'] = 'Emailkonto bereits vergeben';
            redirect("/register",302);
        }
        $user->register();
        redirect("/login",302);

    }
}
