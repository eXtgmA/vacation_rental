<?php

namespace src\controller;

use src\helper\DatabaseTrait;
use src\helper\ValidationTrait;

include_once("../src/helper/redirect.php");
include_once("../src/config/database.php");

class BaseController
{
    use DatabaseTrait, ValidationTrait;

    public function __construct()
    {
    }

    protected function redirectIfNotLoggedIn(): void
    {
        if (!isset($_SESSION['user'])) {
            header('location: /dashboard', true, 302);
        }
    }

    /**
     * @param int|null $idParam
     * @return void
     */
    protected function notFoundOnMissingParam(int $idParam = null)
    {
        if ($idParam == null) {
            new ViewController('notFound');
            die();
        }
    }

    /**
     * @param int $idParam
     * @param string $class
     * @throws \Exception
     */
    protected function notFoundOnMissingObject(int $idParam, string $class):object
    {
        $class = '\src\models\\' . $class;
        $object=$this->find($class, 'id', $idParam, 1);
        if (!$object) {
            new ViewController('notFound');
            die();
        }
        return $object;
    }

    /**
     * @param int|null $idParam
     * @param string $class
     * @throws \Exception
     */
    protected function forceParam(int $idParam = null, string $class)//@phpstan-ignore-line
    {
        $this->notFoundOnMissingParam($idParam);
        /** @var int $idParam */
        $result=$this->notFoundOnMissingObject($idParam, $class);
        return $result;
    }
}
