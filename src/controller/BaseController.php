<?php

namespace src\controller;

use src\helper\DatabaseTrait;
use src\helper\ValidationTrait;
use src\helper\SecurityTrait;

include_once("../src/helper/redirect.php");
include_once("../src/config/database.php");

class BaseController
{
    use DatabaseTrait, ValidationTrait,SecurityTrait ;

    public function __construct()
    {
    }

    protected function redirectIfNotLoggedIn(): void
    {
        if (!isset($_SESSION['user'])) {
            redirect('/dashboard', 302);
            die();
        }
    }
    protected function redirectIfLoggedIn(): void
    {
        if (isset($_SESSION['user'])) {
            redirect('/dashboard', 302);
            die();
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
    protected function notFoundOnMissingObject(int $idParam, string $class): object
    {
        $class = ucfirst($class);
        $class = '\src\models\\' . $class;
        $object = $this->find($class, 'id', $idParam, 1);
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
        $result = $this->notFoundOnMissingObject($idParam, $class);
        return $result;
    }

    /**
     * @param int|null $id
     * @param string $class
     * @param string $back
     * @return void
     * @throws \Exception
     */
    public function isUserAllowedHere($id, $class, $back):void
    {


        $user = $this->find('\src\models\User', 'id', $_SESSION['user'], 1);
        $item = $this->find("\src\models\\$class", 'id', $id, 1);
//      if it is a house, direct comparison is possible
        if (strtolower($class) == 'house') {
            if ($item->getOwnerId() != $user->getId()) {
                redirect($back, 302);
                die();
            }
        }

//        if it is a option, check through house
        if (strtolower($class) == 'option') {
            $houseId = ($item->getHouseId());

            $house = $this->find('\src\models\House', 'id', $houseId, 1);
            if ($house->getOwnerId() != $user->getId()) {
                var_dump('noch das');
                redirect($back, 302);
            }
        }
    }
}
