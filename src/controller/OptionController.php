<?php
namespace src\controller;

use src\models\Option;

class OptionController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getIndex(int $id): void
    {
        $option = new Option();
        $option = $option->getOption($id);
        new ViewController('showOneOption');
    }

    public function getCreate() : void
    {
        new ViewController("createNewOption");
    }

    public function postCreate() : void
    {
        $param = [];
        foreach ($_REQUEST as $key => $value) {
            $param[$key] = $value;
        }
        $option = new Option();
        $option->addOption($param);
    }
}
