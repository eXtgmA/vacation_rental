<?php
namespace src\controller;

class ViewController extends BaseController
{
    public function __construct($viewName)
    {
        parent::__construct();
        include_once("..\\src\\views\\{$viewName}.view.php");
    }
}
