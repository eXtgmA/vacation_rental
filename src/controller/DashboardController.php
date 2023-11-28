<?php
namespace src\controller;

class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        parent::redirectIfNotLoggedIn();
    }

    public function getindex(mixed $formdata=null): void
    {
        new ViewController('dashboard');
    }

}
