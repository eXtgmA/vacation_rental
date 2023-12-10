<?php
namespace src\controller;

use src\helper\DatabaseTrait;
use src\models\Booking;
use src\models\Bookingposition;
use src\models\House;

class BookingController extends BaseController
{
    use DatabaseTrait;
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

    public function getCreateBookingposition(int $houseId) : void
    {
        // fetch a house object by id
        $query = "SELECT * FROM houses WHERE id={$houseId} LIMIT 1;";
        $houseResult = $this->fetch($query);
        $param["house"] = $houseResult->fetch_object('src\models\House');
        // todo fetch all options by house id
        $param["options"] = null;
        // todo fetch all bookings for a house (then translate into days)
        $param["bookedDays"] = null; // (array of days)
        new ViewController('bookingCreate', $param);
    }

    public function postCreateBookingposition() : void
    {
        // todo:
            // fetch the one booking where "is_confirmed" is set to false (if exists)
            // if a non-confirmed booking exists => new Booking($existingBooking); and append the new bookingposition
            // else:
        // insert a new booking into database and use it
        try {
            $query = "INSERT INTO bookings (is_confirmed, user_id) VALUES (0,'{$_SESSION['user']}');";
            /** @var Booking $booking */
            $booking = $this->storeAndReturn($query, "\src\models\Booking");
            // insert a new bookingposition into database
            $query_bp = "INSERT INTO bookingpositions (date_start, date_end, house_id, booking_id)"
                . " VALUES ('{$_POST['dateStart']}','{$_POST['dateEnd']}','{$_POST['houseId']}','{$booking->getId()}');";
            $this->store($query_bp);
        } catch (\Exception $e) {
            $_SESSION['message'] = "Hoppla, da ist wohl etwas schief gelaufen";
            redirect('/booking/create/' . $_POST['houseId'], 302, $_POST);
            die();
        }
        $_SESSION['message'] = "Bookingposition erfolgreich angelegt";
        redirect('/booking/create/' . $_POST['houseId'], 302);
        die();
    }

    public function postBooking(int $bookingId) : void
    {
        // todo
    }
}
