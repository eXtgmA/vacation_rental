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

    /**
     * @param int|null $houseId
     * @return void
     * @throws \Exception
     *
     * deliver creation page for a new Option
     * redirect user to offers if he tries to edit/create an option for non belonging house
     */
    public function getCreate(int $houseId = null) : void
    {
        $this->forceParam($houseId, 'House');
        $this->isUserAllowedHere($houseId,'house','offer');
        new ViewController("OptionCreate", $houseId);
    }

    /**
     * @param int $houseId
     * @return void
     * @throws \Exception
     *
     * creating process of a new house belonging option
     * includiing storage process of images in db and disk
     */
    public function postCreate(int $houseId) : void
    {
        $this->forceParam($houseId,'house');
        $this->isUserAllowedHere($houseId,'house','/offer');
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

    /**
     * @param int|null $houseId
     * @return void
     * @throws \Exception
     *
     * indexpage for all Options belongign to a house
     */
    public function getShowall(int $houseId = null) : void
    {
        $house = $this->forceParam($houseId, 'house');
        $this->isUserAllowedHere($houseId, 'house', '/offer');

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
