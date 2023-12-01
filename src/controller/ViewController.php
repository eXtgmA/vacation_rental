<?php
namespace src\controller;

class ViewController extends BaseController
{
    public function __construct(string $viewName)
    {
        parent::__construct();
        include_once(__DIR__."/../views/{$viewName}.view.php");
    }
}
