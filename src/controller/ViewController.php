<?php

namespace src\controller;

class ViewController extends BaseController
{
    public mixed $param;

    /**
     * @param string $viewName
     * @param mixed|null $param
     */
    public function __construct(string $viewName, mixed $param = null)
    {
        $this->param = $param;
//        parent::__construct();
        include_once(__DIR__ . "/../views/{$viewName}.view.php");
    }
}
