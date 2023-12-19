<?php

namespace src\controller;

use src\models\Booking;
use src\models\Bookingposition;

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

    public function getCreateBookingposition(int $houseId = null): void
    {
        $house=$this->forceParam($houseId, 'house');

        $param["house"] = $house;

        // fetch all options related to the given house
        $param["options"] = $house->getAllOptions();
        if ($param["options"] != false) {
            foreach ($param["options"] as $key => $option) {
                // sort out all disabled options
                if ($option->isDisabled()) {
                    unset($param["options"][$key]);
                }
            }
        }

        // todo fetch all bookings for a house (then translate into days)
        $param["bookedDays"] = null; // (array of days)
        new ViewController('bookingCreate', $param);
    }

    public function postCreateBookingposition(): void
    {
        try {
            // get the one and only booking that is not confirmed for our actual user
            $query = "select * from bookings where user_id = {$_SESSION['user']} and is_confirmed = 0 limit 1";
            $sql=$this->connection()->query($query);
            $booking = null; //fallback initializing
            if ($sql instanceof \mysqli_result) {
                $booking= $sql->fetch_object('\src\models\Booking');
            }

            if ($booking == null) {
                // if no booking found => insert new booking into database and use this one
                $booking = new Booking(['is_confirmed' => 0, 'user_id' => $_SESSION['user'], 'booked_at' => null]);
                $booking->save();
            }

            $param = $_POST;
            /** @var Booking $booking */
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

    public function postDeleteBookingposition(int $id = null): void
    {
        $bps=$this->forceParam($id, 'bookingposition');
        try {
            /** @var Bookingposition $bps */
                $bps->deleteBookingposition();
        } catch (\Exception $e) {
            error_log("Bookingposition ({$id}) could not be deleted from cart");
            $_SESSION['message'] = "Hoppla, da ist wohl etwas schief gelaufen";
            redirect('/cart', 500);
            die();
        }
        redirect('/cart', 302);
    }
}
