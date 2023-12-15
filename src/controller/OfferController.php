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

        // save front image
        $uuidF = Image::imageToDisk($_FILES['front-image-input']);
        $frontimage = new Image(['house_id'=>$house->getId(),'typetable_id'=>1,'uuid'=>$uuidF]);
        $frontimage->save();

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
