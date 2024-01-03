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

    public function getCreateBookingposition(int $houseId = null): void
    {
        $house=$this->forceParam($houseId, 'House');

        // redirect if house is disabled
        $this->redirectIfHouseIsDisabled($house);

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

        /** @var House $house */
        $house = $this->find('\src\models\House', 'id', $_POST['house_id'], 1);

        // prevent booking of disabled houses
        $this->redirectIfHouseIsDisabled($house);

        // prevent to book on top of already booked days
        if (!$house->isTimeFrameAvailable($_POST['date_start'], $_POST['date_end'])) {
            $_SESSION['message'] = "Ausgebucht! Bitte wähle ein anderes Zeitfenster.";
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

            /** @var Booking $booking */
            if ($booking == null) {
                // if no booking found => insert new booking into database and use this one
                $booking = new Booking(['is_confirmed' => 0, 'user_id' => $_SESSION['user'], 'booked_at' => null]);
                $booking->save();
            } else {
                // prevent user from booking the same house in the same time frame multiple times
                if (!$booking->isTimeFrameAvailableInCart($_POST['date_start'], $_POST['date_end'], $house->getId())) {
                    $_SESSION['message'] = "Du hast bereits eine Buchung für diesen Zeitraum im Warenkorb.";
                    redirect('/booking/create/'.$_POST['house_id'], 302, $_POST);
                    die();
                }
            }

            // prepare data for bookingposition
            $param = $_POST;
            /** @var Booking $booking */
            $param['booking_id'] = $booking->getId();

            $priceList = [];
            // prepare option names with prices
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
            // prepare price data
            $priceList['price_per_night'] = $param['price_per_night'];
            $priceList['night_count'] = $param['night_count'];
            $priceList['total_price'] = $param['total_price'];

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

    public function redirectIfHouseIsDisabled(House $house) : void
    {
        // redirect if house is disabled
        if ($house->getIsDisabled()) {
            $_SESSION['message'] = "Die gewählte Ferienwohnung steht aktuell nicht zur Verfügung.";
            redirect("/offer/find", 302);
            die();
        }
    }
}
