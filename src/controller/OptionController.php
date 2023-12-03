<?php
namespace src\controller;

use src\models\Option;
use src\models\Image;

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
        // feed image parameters into image creation
        $image_param["id"] = $_REQUEST["image_id"];
        $image_param["uuid"] = $_REQUEST["uuid"];
        $image_param["house_id"] = $_REQUEST["house_id"];
        $image_param["typetable_id"] = $_REQUEST["typetable_id"];
//        $image = new Image();
//        $image->addImage($image_param);

        // option parameters
        $option_param["id"] = $_REQUEST["option_id"];
        $option_param["name"] = $_REQUEST["name"];
        $option_param["description"] = $_REQUEST["description"];
        $option_param["price"] = $_REQUEST["price"];
        $option_param["is_disabled"] = $_REQUEST["is_disabled"];
        $option_param["house_id"] = $_REQUEST["house_id"];
//        $option_param["image_id"] = $image->getId();
        $option = new Option();
        $option->addOption($option_param);
    }
}
