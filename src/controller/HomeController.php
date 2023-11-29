<?php
namespace src\controller;

class HomeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getindex(mixed $formdata = null): void
    {
        new ViewController('home');
    }
}
