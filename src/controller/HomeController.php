<?php
namespace src\controller;

class HomeController extends BaseController
{
    public function __construct()

    {
    }

    public function getindex($formdata=null)
    {
        new ViewController('home');
    }


}