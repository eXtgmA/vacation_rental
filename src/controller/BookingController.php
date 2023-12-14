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
        try {
            // get the one and only booking that is not confirmed
            $booking = $this->find('\src\models\Booking', 'is_confirmed', 0, 1);
            if ($booking == null) {
                // if no booking found => insert new booking into database and use this one
                $booking = new Booking(['is_confirmed' => 0, 'user_id' => $_SESSION['user'], 'booked_at' => null]);
                $booking->save();
            }

            $param = $_POST;
            $param['booking_id'] = $booking->getId();
            $param['price_detail_list']=null;

            $bookingposition = new Bookingposition($param);
            $bookingposition->save();

            $_SESSION['message'] = "Buchungsposition erfolgreich angelegt";
            redirect('/cart', 302);
        } catch (\Exception $e) {
            $_SESSION['message'] = "Hoppla, da ist wohl etwas schief gelaufen";
            redirect('/booking/create/' . $_POST['houseId'], 302, $_POST);
            die();
        }
    }
}
