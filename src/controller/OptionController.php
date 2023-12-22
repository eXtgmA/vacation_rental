<?php
namespace src\controller;

use src\models\House;
use src\models\Image;
use src\models\Option;
use src\models\User;

class OptionController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

//    public function getIndex(int $id): void
//    {
//        var_dump('hier');
//        die();
//        $option = new Option();
//        $option = $option->getOption($id);
//        new ViewController('showOneOption');
//    }

    public function getCreate(int $houseId = null) : void
    {
        $this->forceParam($houseId, 'House');
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
        // save image to disk and db
        try {
            $uuid = Image::imageToDisk($_FILES['optionimage']);
        } catch (\Exception $e) {
            $_SESSION['message'] = "Foto(s) wurde(n) nicht korrekt übergeben";
            redirect($_SESSION['previous'], 302, $_POST);
            die();
        }
        // save image into db // todo : use transaction (rollback and unlink in case of error)
        $image = new Image(['house_id' => $houseId, 'typetable_id' => 3, 'uuid' => $uuid]);
        $image->save();

        // validate input of option
        $option = $_POST;
        $option['house_id'] = $houseId;
        $option['image_id'] = $image->getId();
        $this->validateInput('Option', $_POST);
        // save option
        $option=new Option($option);
        $option->save();

        $_SESSION['message'] = "Option wurde erfolgreich angelegt";
        redirect("/option/showall/".$houseId, 302);
    }

    public function getShowall(int $houseId = null) : void
    {
        $house = $this->forceParam($houseId, 'house');
        $this->isUserAllowedHere($houseId, 'house', '/offer');
        // todo: check if house is owned by user (see above in postCreate() )
        // initialize house
//        try {
//            /** @var House $house */
//            $house = $this->find('\src\models\House', 'id', $houseId, 1);
//        } catch (\Exception $e) {
//            $_SESSION['message'] = "Das gewählte Haus existiert nicht";
//            redirect($_SESSION['previous'], 500);
//            die();
//        }
        // get all options related to house from db
        /** @var mixed[] $allOptions */
        $allOptions = $house->getAllOptions();
        $allOptions['houseId'] = $houseId;
        new ViewController("optionShowall", $allOptions);
    }

    public function getEdit(int $optionId = null): void
    {
        $this->forceParam($optionId, 'option');
        $this->isUserAllowedHere($optionId, 'option', '/offer');
        if (!$optionId) {
            // fallback when missing param in url
            redirect('/dashboard', 302);
        }

        // get option
        $param['option'] = $this->find('\src\models\Option', 'id', $optionId, 1);
        new ViewController("optionEdit", $param);
    }

    public function postEdit(int $optionId): void
    {
        if (!$optionId) {
            // fallback when missing param in url
            redirect('/dashboard', 302);
        }

        // update option data
        /** @var Option $option */
        $option = $this->find('\src\models\Option', 'id', $optionId, 1);
        $option->update($_POST);

        // update image
        $this->updateImage($option, $_FILES['option-image-input']);

        redirect("/option/edit/{$optionId}", 302);
    }

    public function postDelete(int $optionId = null): void
    {
        /** @var Option $option */
        $option=$this->forceParam($optionId, 'option');
        // todo check if rest is needed, maybe more params to function
        try {
            // delete option (related image included)
//            $option = $this->find('\src\models\Option', 'id', $optionId, 1);
            $option->deleteOption();
        } catch (\Exception $e) {
            // database error during deletion
            redirect($_SESSION['previous'], 500);
        }
        // deletion successful
        redirect($_SESSION['previous'], 302);
    }

    public function posttoggleStatus(int $id): void
    {
        /** @var Option $option */
        $option=$this->forceParam($id, 'option');
//        $option = $this->find('\src\models\Option', 'id', $id, 1);
        $option->toggleStatus();
        header('location: /option/showall/'.$option->getHouseId(), true, 302);
    }

    /**
     * Update option image
     *
     * @param Option $option
     * @param array<string> $imgInput
     * @return void
     */
    public function updateImage(Option $option, array $imgInput): void
    {
        // update image
        if ($imgInput['name'] != '') {
            try {
                /** @var Image $image */
                $image = $this->find('\src\models\Image', 'uuid', $option->getOptionImage(), 1);
                if ($image != null) {
                    $image->updateImage($imgInput);
                }
            } catch (\Exception $e) {
                error_log("Image of option could not be updated because: ". $e);
                $_SESSION['message'] = "Das Bild konnte nicht geändert werden";
                redirect("/option/edit/{$option->getId()}", 302);
                die();
            }
        }
    }
}
