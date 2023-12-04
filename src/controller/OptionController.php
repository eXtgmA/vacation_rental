<?php
namespace src\controller;

use src\models\Option;
use src\models\User;

//use src\models\Image;
//use src\models\Typetable;

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
        // check if house is owned by user
        $user = new User();
//        if (!$user->isHouseOwned($_SESSION["user"], $_REQUEST["house_id"])) { // todo: activate after implementing function
//            error_log("User (" . $_SESSION["user"] . ") tried to access house (" . $_REQUEST["house_id"] . ") to change an option, but is not the owner.");
//            $_SESSION["message"] = "Sie sind nicht berechtigt diese Optionen anzulegen.";
//            header("location: {$_SERVER['HTTP_ORIGIN']}/createoption", true, 403);
//        }

        try {
            // feed image parameters into image creation
            $image_param["house_id"] = $_REQUEST["house_id"];
//            $typetable = new Typetable(); // todo: activate after implementing typetable function
//            $image_param["typetable_id"] = $typetable->getIdFromType("Option");
//            $image = new Image();
//            $image = $image->addImage($image_param);

            // option parameters
            $option_param["name"] = $_REQUEST["name"];
            $option_param["description"] = $_REQUEST["description"];
            $option_param["price"] = $_REQUEST["price"];
            $option_param["house_id"] = $_REQUEST["house_id"];
//            $option_param["image_id"] = $image->getId();
            $option = new Option();
            $option->addOption($option_param);
        } catch (\Exception $exception) {
            // todo if fail hopopla Fehler  , + POST-data zurückgeben!
            $_SESSION["message"] = $exception->getMessage();
            header("location: {$_SERVER['HTTP_ORIGIN']}/createoption", true, 302);
        }
    }
}
