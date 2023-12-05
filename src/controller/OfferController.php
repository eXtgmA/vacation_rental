<?php

namespace src\controller;

use src\models\House;

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
        $param = [];
        foreach ($_REQUEST as $key => $value) {
            $param[$key] = $value;
        }
        $house = new House();
        $frontimage=$_FILES['frontimage'];
        $house->addhouse($param,$frontimage);
    }


    /**
     * @return mixed[]|null
     */
    public function getAllHousesBelongingToTheCurrentUser(): ?array
    {
        // Fetch houses from db as HOUSE Object
        $housesResult = $this->connection->query("Select * From houses where owner_id = {$_SESSION['user']}");
        // add each object to array
        if ($housesResult instanceof \mysqli_result) {
            $houses = [];
            while ($house = $housesResult->fetch_object('src\models\House')) {
                /** @var House $house */
                $houses[] = $house;
            }
            return $houses;
        }
        return null;
    }

    /**
     * @param int $id
     * @return void
     */
    public function posttoggleStatus(int $id): void
    {
        $query = "Select * from houses where id = {$id} limit 1";
        $result = $this->connection->query($query);
        if ($result!=false && $result instanceof \mysqli_result) {
            while ($house=$result->fetch_object('src\models\House')) {
                /** @var House $house */
                $house->toggleStatus();
            };
        }
        header('location: /offer', true, 302);
    }

    /**
     * @param $id
     * @return void
     */
    public function getshow(int $id):void
    {
        // fetch house by id
        $query = "Select * from houses where id = {$id} limit 1";
        $result = $this->connection->query($query);
        $house = null; // initialize to avoid undefined variable
        if($result instanceof \mysqli_result){
           $house= $result->fetch_object('src\models\House');
        }
            new ViewController("offerDetail", $house);
        }
}
