<?php
namespace src\controller;

class DashboardController
{
    public function __construct()

    {
    }

    public function getindex($formdata=null)
    {
        new ViewController('dashboard');
    }

}