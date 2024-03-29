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
        $this->redirectIfNotLoggedIn();
        $this->forceParam($houseId, 'House');
        $this->isUserAllowedHere($houseId, 'house', 'offer');
        new ViewController("optionCreate", $houseId);
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
        $this->sanitize($_POST);
        $this->forceParam($houseId, 'house');
        $this->isUserAllowedHere($houseId, 'house', '/offer');

        // save image to disk and db
        try {
            $uuid = Image::imageToDisk($_FILES['optionimage']);
        } catch (\Exception $e) {
            $_SESSION['message'] = "Hochladen des Bildes fehlgeschlagen";
            redirect($_SESSION['previous'], 302, $_POST);
            die();
        }
        // save image into db
        $image = new Image(['house_id' => $houseId, 'typetable_id' => 3, 'uuid' => $uuid]);
        $image->save();

        // validate input of option
        $option = $_POST;
        $option['house_id'] = $houseId;
        $option['image_id'] = $image->getId();
        // if validation fails => redirect to previous page with notification
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
        $this->redirectIfNotLoggedIn();
        $house = $this->forceParam($houseId, 'house');
        $this->isUserAllowedHere($houseId, 'house', '/offer');

        // get all options related to house from db
        /** @var mixed[] $allOptions */
        $allOptions = $house->getAllOptions();
        $allOptions['houseId'] = $houseId;
        new ViewController("optionShowall", $allOptions);
    }

    /**
     * @param int|null $optionId
     * @return void
     * @throws \Exception
     *
     * Deliver detail/edit page for a specific option belonging to a house
     */
    public function getEdit(int $optionId = null): void
    {
        $this->redirectIfNotLoggedIn();
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

    public function postEdit(int $optionId = null): void
    {
        $this->sanitize($_POST);
        $this->forceParam($optionId, 'option');
        $this->isUserAllowedHere($optionId, 'option', '/offer');

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

        $_SESSION['message'] = "Änderungen gespeichert";
        redirect("/option/edit/{$optionId}", 302);
    }

    public function postDelete(int $optionId = null): void
    {
        /** @var Option $option */
        $option=$this->forceParam($optionId, 'option');
        $this->isUserAllowedHere($optionId, 'option', '/offer');
        try {
            $option->deleteOption();
        } catch (\Exception $e) {
            // database error during deletion
            $_SESSION['message'] = "Löschen fehlgeschlagen";
            redirect($_SESSION['previous'], 302);
            die();
        }
        // deletion successful
        $_SESSION['message'] = "Option gelöscht";
        redirect($_SESSION['previous'], 302);
    }

    public function posttoggleStatus(int $id): void
    {
        /** @var Option $option */
        $option=$this->forceParam($id, 'option');
        $option->toggleStatus();
        redirect('/option/showall/'.$option->getHouseId(), 302);
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
