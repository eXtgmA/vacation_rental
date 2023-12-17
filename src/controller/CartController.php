<?php
namespace src\controller;

use \src\models\Booking;
use \src\models\Bookingposition;

class CartController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCart(): void
    {
        try {
            // get the one and only booking where "is_confirmed" equals false belonging to current user
            $query = "select * from bookings where user_id = {$_SESSION['user']} and is_confirmed = 0 limit 1";
            $sql=$this->connection()->query($query);
            $booking = null; // fallback initializing
            if($sql instanceof \mysqli_result){
                $booking= $sql->fetch_object('\src\models\Booking');
            }
            $param['booking'] = $booking;

            /** @var Booking $booking */
            if ($booking != null) {
                // get all bookingpositions related to this booking
                $param["bookingpositions"] = $booking->getAllBookingpositions();


                // if no positions found show empty cart
                if ($param["bookingpositions"] == false) {
                    new ViewController('cart');
                    die();
                }


                // get all houses related to all bookingpositions
                $houseIds = [];
                foreach ($param["bookingpositions"] as $bp) {
                    // fetch house if not already loaded
                    if (!in_array($bp->getHouseId(), $houseIds)) {
                        $param["houses"][$bp->getHouseId()] = $this->find('\src\models\House', 'id', $bp->getHouseId(), 1);
                        $houseIds[] = $bp->getHouseId();
                    }
                }

                // check that at least one house has been fetched
                if (empty($param["houses"])) {
                    throw new \Exception("No house loaded");
                }
            }
        } catch (\Exception $e) {
            $_SESSION['message'] = "Ein unerwarteter Fehler ist aufgetreten";
            new ViewController('cart');
            die();
        }
        new ViewController('cart', $param);
    }
}
