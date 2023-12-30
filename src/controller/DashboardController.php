<?php
namespace src\controller;

use src\models\House;

class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getindex(mixed $formdata = null): void
    {
        // reset search-data of session
        if (isset($_SESSION['search-data'])) {
            unset($_SESSION['search-data']);
        }
        // reset session filter data
        $_SESSION['filter'] = ['tags'=>[], 'features'=>[]];

        // pick 3 random offers to display on the dashboard
        /** @var array<string> $suggestedHouses */
        $suggestedHouses = [];
        $query = "SELECT * FROM houses WHERE is_disabled=0 ORDER BY RAND() LIMIT 3;";
        $result = $this->connection()->query($query);
        if ($result instanceof \mysqli_result) {
            while ($house = $result->fetch_object('\src\models\House')) {
                /** @var House $house */
                $tmp['name'] = $house->getName();
                $tmp['city'] = $house->getCity();
                $tmp['price'] = $house->getPrice();
                $tmp['image'] = "/images/".$house->getFrontImage();
                $suggestedHouses[] = $tmp;
            }
        }

        // if less than 3 houses in database, fill demo house array up with default houses
        $defaultHousesData = [
            ["name"=>"Haus auf grüner Wiese", "city"=>"Berlin", "price"=>"750", "image"=>"/assets/haus1.jpg"],
            ["name"=>"Wohnung am Hang", "city"=>"München", "price"=>"100", "image"=>"/assets/haus2.jpg"],
            ["name"=>"Modernes Holzhaus", "city"=>"Hamburg", "price"=>"50", "image"=>"/assets/haus3.jpg"]
        ];
        for ($i=0; count($suggestedHouses) < 3; $i++) {
            $suggestedHouses[] = $defaultHousesData[$i];
        }

        new ViewController('dashboard', $suggestedHouses);
    }
}
