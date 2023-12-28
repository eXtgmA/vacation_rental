<?php
namespace src\controller;

class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getindex(mixed $formdata = null): void
    {
        // reset search-data of session
        if (isset($_SESSION['search-data'])) {
            unset($_SESSION['search-data']);
        }
        // todo : reset filter data

        new ViewController('dashboard');
    }
}
