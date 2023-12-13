<?php

namespace src\controller;

use src\models\Booking;
use src\models\Bookingposition;
use src\models\House;

class BookingController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getIndex(int $userId, mixed $formdata = null): void
    {
    }

//    /**
//     * Display one booking specified by given id
//     *
//     * @param int $bookingId
//     * @return void
//     */
//    public function getBooking(int $bookingId) : void
//    {
//        $booking = new Booking();
//        new ViewController('bookingDetail', $booking); // todo : specify filename
//    }

    public function getCreateBookingposition(int $houseId): void
    {
        // fetch a house object by id
        $house = $this->find('\src\models\House', 'id', $houseId, 1);
        if ($house === null) {
            $_SESSION['message'] = "Hoppla, da ist wohl etwas schief gelaufen";
            redirect('/dashboard', 302);
        }
        $param["house"] = $house;
        // todo fetch all options by house id
        $param["options"] = null;
        // todo fetch all bookings for a house (then translate into days)
        $param["bookedDays"] = null; // (array of days)
        new ViewController('bookingCreate', $param);
    }

    public function postCreateBookingposition(): void
    {
        // todo:
        // fetch the one booking where "is_confirmed" is set to false (if exists)
        // if a non-confirmed booking exists => new Booking($existingBooking); and append the new bookingposition
        // else:
        // insert a new booking into database and use it
        try {
            $booking = new Booking(['is_confirmed' => 0, 'user_id' => $_SESSION['user'],'booked_at'=>null]);
            $booking->save();

            $param = $_POST;
            $param['booking_id'] = $booking->getId();
            $param['price_detail_list']=null;

            $bookingposition = new Bookingposition($param);
            $bookingposition->save();

            $_SESSION['message'] = "Bookingposition erfolgreich angelegt";
            var_dump($booking->getId());
            redirect('/booking/cart/' . $booking->getId(), 302);
        } catch (\Exception $e) {
            $_SESSION['message'] = "Hoppla, da ist wohl etwas schief gelaufen";
            redirect('/booking/create/' . $_POST['houseId'], 302, $_POST);
            die();
        }
    }

    public function getCart(int $bookingId): void
    {
        try {
            // get the booking from db
            /** @var Booking $booking */
            $booking = $this->find('\src\models\Booking', 'id', $bookingId, 1);
            $param["booking"] = $booking;// todo : get the one and only booking where is_confirmed equals false
            // get all bookingpositions related to this booking
            $param["bookingpositions"] = $param["booking"]->getAllBookingpositions();
            // todo : get all houses related to all bookingpositions
        } catch (\Exception $e) {
            $_SESSION['message'] = "Buchung wurde nicht gefunden";
            new ViewController('cart');
            die();
        }
        new ViewController('cart', $param);
    }

    public function getCheckout(int $bookingId): void
    {
        try {
            // todo : fetch the one an only booking where "is_confirmed" equals false
            $bookingResult = $this->find('\src\models\Booking', 'id', $bookingId, 1);
            $param["booking"] = $bookingResult;
            // get all bookingpositions related to this booking
            $param["bookingpositions"] = $param["booking"]->getAllBookingpositions();
            // todo : get all houses related to all bookingpositions
            // set $param["houses"]["house_id"] = house-object
        } catch (\Exception $e) {
            $_SESSION['message'] = "Buchungsdaten wurden nicht gefunden";
            new ViewController('checkout');
            die();
        }
        new ViewController('checkout', $param);
    }

    public function postCheckout(int $bookingId): void
    {
        try {
            // todo : set "is_confirmend" to true and save timestamp in "booked_ad" in db
            $query = "UPDATE bookings SET is_confirmed=1, booked_at=CURRENT_TIMESTAMP() WHERE id={$bookingId};";
            /* @phpstan-ignore-next-line */
            $this->store($query);   // todo: this should be refactored into some of the new methods
        } catch (\Exception $e) {
            $_SESSION['message'] = "Beim Bezahlvorgang ist etwas schiefgelaufen.";
            redirect('/booking/checkout/' . $bookingId, 302);
            die();
        }
        new ViewController('checkoutSuccess');
    }

    public function postBooking(int $bookingId): void
    {
        // todo
    }
}
