<?php

namespace src\controller;

use src\models\Booking;
use src\models\Bookingposition;
use src\models\House;
use src\models\Option;

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
        $house=$this->forceParam($houseId, 'House');

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

        // fetch all booked dates
        $param["bookedDays"] = $house->getBookedDates();

        new ViewController('bookingCreate', $param);
    }

    public function postCreateBookingposition(): void
    {
        // make sure the start date is truly before the end date
        if ($_POST['date_start'] >= $_POST['date_end']) {
            $_SESSION['message'] = "Das Anreisedatum muss vor dem Abreisedatum liegen";
            redirect('/booking/create/'.$_POST['house_id'], 302, $_POST);
            die();
        }

        // prevent to book on top of already booked days
        /** @var House $house */
        $house = $this->find('\src\models\House', 'id', $_POST['house_id'], 1);
        if (!$house->isTimeFrameAvailable($_POST['date_start'], $_POST['date_end'])) {
            $_SESSION['message'] = "Ausgebucht! Bitte wählen Sie ein anderes Zeitfenster.";
            redirect('/booking/create/'.$_POST['house_id'], 302, $_POST);
            die();
        }

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

            // prepare data for bookingposition
            $param = $_POST;
            /** @var Booking $booking */
            $param['booking_id'] = $booking->getId();

            $priceList = [];
            if (isset($_POST['option'])) {
                foreach ($_POST['option'] as $oIn) {
                    /** @var Option $option */
                    $option = $this->find('\src\models\Option', 'id', $oIn, 1);
                    if ($option != null) {
                        // add to price_detail_list
                        $priceList['options'][$option->getName()] = $option->getPrice();
                    }
                }
            }
            // todo save gesamtpreis in price_detail_list (when calculated with JS)
//            $priceList['price'] = $param['price'];
            // encode price_detail_list to json string
            $param['price_detail_list'] = json_encode($priceList);

            $bookingposition = new Bookingposition($param);
            $bookingposition->save();

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
            $_SESSION['message'] = "Die Position konnte nicht gelöscht werden";
            redirect('/cart', 500);
            die();
        }
        redirect('/cart', 302);
    }
}
