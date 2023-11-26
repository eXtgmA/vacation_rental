<?php
namespace src\controller;

class HomeController
{
    public function __construct()

    {
    }

    public function getindex($formdata=null)
    {
        new ViewController('home');
    }


}