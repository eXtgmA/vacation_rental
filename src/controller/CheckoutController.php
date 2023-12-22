<?php

namespace src\controller;

use src\models\Booking;

class CheckoutController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCheckout(): void
    {
        try {

            // get the one and only booking where "is_confirmed" equals false belonging to current user
            $query = "select * from bookings where user_id = {$_SESSION['user']} and is_confirmed = 0 limit 1";
            $sql = $this->connection()->query($query);
            $booking = null; // fallback initializing
            if ($sql instanceof \mysqli_result) {
                $booking = $sql->fetch_object('\src\models\Booking');
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
                foreach ($param["bookingpositions"] as $key => $bp) {
                    // fetch house if not already loaded
                    $house = null;
                    if (!in_array($bp->getHouseId(), $houseIds)) {
                        // store house in array
                        $house = $this->find('\src\models\House', 'id', $bp->getHouseId(), 1);
                        $param["houses"][$house->getId()] = $house;
                        $houseIds[] = $bp->getHouseId();
                    } else {
                        $house = $param['houses'][$bp->getHouseId()]; // @phpstan-ignore-line
                    }
                    // check if booking is still possible
                    if (!$house->isTimeFrameAvailable($bp->getDateStart(), $bp->getDateEnd())) {
                        // if booking is not available anymore => delete bookingposition
                        $bp->deleteBookingposition();
                        unset($param["bookingpositions"][$key]);
                    }
                }
                // check that at least one house has been fetched
                if (empty($param["houses"])) {
                    throw new \Exception("No house loaded");
                }
            }
        } catch (\Exception $e) {
            $_SESSION['message'] = "Buchungsdaten wurden nicht gefunden";
            new ViewController('checkout');
            die();
        }
        new ViewController('checkout', $param);
    }

    public function postCheckout(int $bookingId = null): void
    {
        $this->forceParam($bookingId, 'booking');
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
