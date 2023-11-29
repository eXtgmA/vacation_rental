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
        // get all Houses belonging to a user
        // Return the House Data to our View
        new ViewController('offerIndex');
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
        $house=new House();
        $house->addhouse($param);
    }
}
