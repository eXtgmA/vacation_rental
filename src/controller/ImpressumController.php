<?php
namespace src\controller;

class ImpressumController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getIndex(mixed $formdata = null): void
    {
        new ViewController("impressum");
    }
}
