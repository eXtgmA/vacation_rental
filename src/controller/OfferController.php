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
        $house->addhouse($param);
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
        while ($house=$result->fetch_object('src\models\House')) {
            $house->toggleStatus();
        };
        header('location: /offer', true, 302);
    }
}
