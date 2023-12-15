<?php

namespace src\controller;

use Exception;
use src\models\House;
use src\models\Image;

class OfferController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getIndex(mixed $formdata = null): void
    {
        $houses = $this->getAllHousesBelongingToTheCurrentUser();
        // Return the House Data to our View
        new ViewController('offerIndex', $houses);
    }

    public function getCreate(): void
    {
        new ViewController('createNewOffer');
    }

    public function postCreate(): void
    {
//        add owner to attributes
        $_POST['owner_id'] = $_SESSION['user'];
//        create house with values
        $this->validateInput('House', $_POST);
        $house = new House($_POST);
        $house->save();

        try {
            // save front image
            $uuidF = Image::imageToDisk($_FILES['front-image-input']);
            $frontimage = new Image(['house_id' => $house->getId(), 'typetable_id' => 1, 'uuid' => $uuidF]);
            $frontimage->save();

            // save layout image
            $uuidL = Image::imageToDisk($_FILES['layout-image-input']);
            $layoutimage = new Image(['house_id' => $house->getId(), 'typetable_id' => 2, 'uuid' => $uuidL]);
            $layoutimage->save();

            // save all additional images (if exist)
            if (isset($_FILES['optional-images'])) {
                $fCount = count($_FILES['optional-images']['name']);
                $oFiles = [];
                for ($i = 0; $i < $fCount; $i++) {
                    // translate input array format
                    $oFiles[$i]['name'] = $_FILES['optional-images']['name'][$i];
                    $oFiles[$i]['full_path'] = $_FILES['optional-images']['full_path'][$i];
                    $oFiles[$i]['type'] = $_FILES['optional-images']['type'][$i];
                    $oFiles[$i]['tmp_name'] = $_FILES['optional-images']['tmp_name'][$i];
                    $oFiles[$i]['error'] = $_FILES['optional-images']['error'][$i];
                    $oFiles[$i]['size'] = $_FILES['optional-images']['size'][$i];

                    // save one additional image
                    $uuidO = Image::imageToDisk($oFiles[$i]);
                    $optionalimage = new Image(['house_id' => $house->getId(), 'typetable_id' => 4, 'uuid' => $uuidO]);
                    $optionalimage->save();
                }
            }
        } catch (Exception $e) {
            try {
                // delete house and all its images
                $house->deleteHouse();
            } catch (Exception $e) {
                error_log("House and its images could not be deleted after creation process has failed");
            }

            // redirect
            $_SESSION['message'] = "Hoppla, da ist wohl etwas schief gelaufen. Das Haus konnte nicht angelegt werden.";
            redirect($_SESSION['previous'], 500, $_POST);
        }

        $_SESSION['message'] = "Das Haus wurde erfolgreich angelegt und kann ab jetzt gemietet werden";
        redirect('/offer', 302);
    }

    /**
     * @return mixed[]|null
     * @throws Exception
     */
    public function getAllHousesBelongingToTheCurrentUser(): ?array
    {
        $houses = $this->find('src\models\House', 'owner_id', $_SESSION['user']);
        return $houses;
    }

    /**
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function posttoggleStatus(int $id): void
    {
        $house = $this->find('\src\models\House', 'id', $id, 1);
        $house->toggleStatus();
        header('location: /offer', true, 302);
    }

    /**
     * @param $id
     * @return void
     * @throws Exception
     */
    public function getshow(int $id):void
    {
        $house = $this->find('\src\models\House', 'id', $id, 1);
        new ViewController("offerDetail", $house);
    }

    public function postDelete(int $houseId): void
    {
        try {
            /** @var House $house */
            $house = $this->find('\src\models\House', 'id', $houseId, 1);
            $house->deleteHouse();
        } catch (Exception $e) {
            $_SESSION['message'] = 'Haus konnte nicht gelöscht werden. Gibt es Buchungen ? (->bookingpositions)';
            redirect($_SESSION['previous'], 500);
        }
        redirect($_SESSION['previous'], 302);
    }

    public function getEdit(int $houseId): void
    {
        $house = $this->find('\src\models\House', 'id', $houseId, 1);
        new ViewController('offerEdit', $house);
    }

    public function postEdit(int $houseId): void
    {
        $house = $this->find('\src\models\House', 'id', $houseId, 1);
        /** @var House $house */
        $param = $_POST;
        $house->update($param);
        redirect("/offer/show/{$houseId}", 302);
    }
}
