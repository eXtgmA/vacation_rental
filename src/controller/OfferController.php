<?php
namespace src\controller;

use src\models\House;

class OfferController extends BaseController
{
    public function __construct()

    {
    }

    public function getIndex($formdata=null)
    {
        // get all Houses belonging to a user
        // Return the House Data to our View
        new ViewController('offerIndex');
    }

    public function getCreate()
    {
        new ViewController('createNewOffer');
    }

    public function postCreate()
    {
        $param = [];
        foreach ($_REQUEST as $key=>$value){
            $param[$key] = $value;
        }
        $house=new House();
        $house->addhouse($param);
    }

}