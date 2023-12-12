<?php

namespace src\controller;

use Exception;
use src\helper\DatabaseTrait;
use src\models\House;
use src\models\Image;

class OfferController extends BaseController
{
    use DatabaseTrait;
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

        $uuid = Image::imageToDisk($_FILES['frontimage']);

        $frontimage = new Image(['house_id'=>$house->getId(),'typetable_id'=>1,'uuid'=>$uuid]);
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

    public function postDelete($houseId)
    {
        $this->delete(model: 'House', id: $houseId);
        redirect($_SESSION['previous'], 302);
    }
}
