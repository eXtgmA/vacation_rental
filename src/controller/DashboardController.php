<?php
namespace src\controller;

class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::redirectIfNotLoggedIn();
    }

    public function getindex($formdata=null)
    {
        new ViewController('dashboard');
    }

}