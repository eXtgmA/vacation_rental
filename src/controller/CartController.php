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
            // get the booking from db
            /** @var Booking|null $booking */
            $booking = $this->find('\src\models\Booking', 'is_confirmed', 0, 1);
            $param["booking"] = $booking;// todo : get the one and only booking where is_confirmed equals false
            if ($booking != null) {
                // get all bookingpositions related to this booking
                $param["bookingpositions"] = $param["booking"]->getAllBookingpositions();
                // todo : get all houses related to all bookingpositions
            }
        } catch (\Exception $e) {
            $_SESSION['message'] = "Ein unerwarteter Fehler ist aufgetreten";
            new ViewController('cart');
            die();
        }
        new ViewController('cart', $param);
    }
}
