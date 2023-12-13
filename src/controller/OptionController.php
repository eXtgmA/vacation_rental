<?php
namespace src\controller;

use src\helper\DatabaseTrait;
use src\models\Image;
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

    public function getCreate(int $houseId) : void
    {
        new ViewController("OptionCreate", $houseId);
    }

    public function postCreate(int $houseId) : void
    {
        // todo: check if house is owned by user
        $user = new User();
//        if (!$user->isHouseOwned($_SESSION["user"], $_REQUEST["house_id"])) { // todo: activate after implementing function
//            error_log("User (" . $_SESSION["user"] . ") tried to access house (" . $_REQUEST["house_id"] . ") to change an option, but is not the owner.");
//            $_SESSION["message"] = "Sie sind nicht berechtigt diese Optionen anzulegen.";
//            header("location: {$_SERVER['HTTP_ORIGIN']}/option/create", true, 403);
//        }
        //upload image and give uuid to option
        // todo check which typetable id to use
        $uuid = Image::imageToDisk($_FILES['optionimage']);
        $image=new Image(['house_id'=>$houseId,'typetable_id'=>1,'uuid'=>$uuid]);
        $image->save();

        $option = $_POST;
        $option['house_id'] = $houseId;
        $option['image_id'] = $image->getId();
        $this->validateInput('Option', $_POST);
        $option=new Option($option);
        $option->save();
        redirect("/option/showall/".$houseId, 302);
    }

    public function getShowall(int $houseId) : void
    {
        // todo: check if house is owned by user (see above in postCreate() )
        $allOptions = $this->find('\src\models\Option', 'house_id', $houseId);
        $allOptions['houseId'] = $houseId;
        new ViewController("optionShowall", $allOptions);
    }


    /**
     * Get all options that belong to a house as array
     *
     *
     * @param int $houseId
     * @return string[]|null
     * @throws \Exception
     */
    public function getAllOptionsByHouseId(int $houseId) : ?array
    {
        return $this->find('\src\models\Option', 'house_id', $houseId);
    }

    public function postDelete(int $optionId): void
    {
        try {
            // delete option (related image included)
            /** @var Option $option */
            $option = $this->find('\src\models\Option', 'id', $optionId, 1);
            $option->deleteOption();
        } catch (\Exception $e) {
            // database error during deletion
            redirect($_SESSION['previous'], 500);
        }
        // deletion successful
        redirect($_SESSION['previous'], 302);
    }
}
