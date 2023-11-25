<?php
namespace src\controller;


class ViewController
{
    public function __construct($viewName)
    {
        include_once("..\\src\\views\\{$viewName}.view.php");
    }
}