<?php
namespace src\controller;

class RegisterController
{
    public function __construct()

    {
    }

    public function getForm($formdata=null)
    {
        new ViewController('register');
    }


}