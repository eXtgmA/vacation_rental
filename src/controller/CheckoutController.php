<?php
namespace src\controller;

use \src\models\Booking;
use \src\models\Bookingposition;

class CheckoutController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCheckout(): void
    {
        try {
            // get the one an only booking where "is_confirmed" equals false
            $booking = $this->find('\src\models\Booking', 'is_confirmed', 0, 1);
            $param["booking"] = $booking;
            if ($booking != null) {
                // get all bookingpositions related to this booking
                $param["bookingpositions"] = $booking->getAllBookingpositions();
                // todo : get all houses related to all bookingpositions
                // set $param["houses"]["house_id"] = house-object
            }
        } catch (\Exception $e) {
            $_SESSION['message'] = "Buchungsdaten wurden nicht gefunden";
            new ViewController('checkout');
            die();
        }
        new ViewController('checkout', $param);
    }

    public function postCheckout(int $bookingId): void
    {
        // todo : check if the booking is owned by the current user
        try {
            // confirm booking and save timestamp
            $query = "UPDATE bookings SET is_confirmed=1, booked_at=CURRENT_TIMESTAMP() WHERE id={$bookingId};";
            $this->connection()->query($query);   // todo: this could be refactored into $booking->update() called by booking model
        } catch (\Exception $e) {
            $_SESSION['message'] = "Beim Bezahlvorgang ist etwas schiefgelaufen.";
            redirect('/checkout', 302);
            die();
        }
        new ViewController('checkoutSuccess');
    }
}
