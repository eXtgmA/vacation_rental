<?php
namespace src\controller;

use src\helper\DatabaseTrait;
use src\models\Option;
use src\models\User;

class OptionController extends BaseController
{
    use DatabaseTrait;
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

    public function getCreate(int $house_id) : void
    {
        new ViewController("OptionCreate", $house_id);
    }

    public function postCreate(int $house_id) : void
    {
        // todo: check if house is owned by user
        $user = new User();
//        if (!$user->isHouseOwned($_SESSION["user"], $_REQUEST["house_id"])) { // todo: activate after implementing function
//            error_log("User (" . $_SESSION["user"] . ") tried to access house (" . $_REQUEST["house_id"] . ") to change an option, but is not the owner.");
//            $_SESSION["message"] = "Sie sind nicht berechtigt diese Optionen anzulegen.";
//            header("location: {$_SERVER['HTTP_ORIGIN']}/option/create", true, 403);
//        }

            // option parameters
            $param["name"] = $_POST["name"];
            $param["description"] = $_POST["description"];
            $param["price"] = $_POST["price"];
            $param["house_id"] = $house_id;
            $optionimage = $_FILES['optionimage'];
        try {
            $option = new Option();
            $option->addOption($param, $optionimage);
        } catch (\Exception $exception) {
            $_SESSION["message"] = "Hoppla! Da ist wohl etwas schief gelaufen!";
            redirect("/option/create".$house_id, 302, $_POST);
        }
    }

    public function getShowall(int $house_id) : void
    {
        // todo: check if house is owned by user (see above in postCreate() )
        $options_all = $this->getAllOptionsByHouseId($house_id);
        $options_all["house_id"] = $house_id;
        new ViewController("optionShowall", $options_all);
    }

    /**
     * Get all options that belong to a house as array
     *
     * @param int $house_id
     * @return mixed[]|null
     * @throws \Exception
     */
    public function getAllOptionsByHouseId(int $house_id) : ?array
    {
        $query = "SELECT * FROM options WHERE house_id = {$house_id};";
        // Fetch option from db as OPTION Object
        $options_result = $this->fetch($query);
        // add each object to array
        $options = [];
        while ($option = $options_result->fetch_object('src\models\Option')) {
            /** @var Option $option */
            $options[] = $option;
        }
        return $options;
    }
}
