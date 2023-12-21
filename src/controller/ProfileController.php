<?php

namespace src\controller;

use src\models\Booking;
use src\models\Bookingposition;
use src\models\User;

class ProfileController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getEdit()
    {
        $id = $_SESSION['user'];
        $user = $this->find('\src\models\User', 'id', $id, 1);
        return new ViewController('profile', $user);
    }

    public function postUpdate()
    {

        /** @var User $user */
        $user = $this->find('\src\models\User', 'id', $_SESSION['user'], 1);
        $user->enteredValidEmail($_POST['email'],"/profile");
        $user->sendUniqueEmail($_POST['email']);

        if ($_POST['password']) {
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }else{
            $_POST['password'] =$user->getPassword();
        }
        $user->update($_POST);


        $_SESSION['message'] = 'Daten wurden erfolgreich gespeichert';
        redirect("/profile", 302);
    }

    public function getHistory() : void
    {
        $this->redirectIfNotLoggedIn();

        try {
            // fetch all bookings from db
            /** @var array<Booking> $bookings */
            $bookings = $this->find('\src\models\Booking', 'user_id', $_SESSION['user']);

            // prepare output data
            $param['bookings'] = [];
            $param['bookingpositions'] = [];
            $param['houses'] = [];

            $houseIds = [];

            foreach ($bookings as $booking) {
                if ($booking->isConfirmed()) {
                    $param['bookings'][] = $booking;
                    // fetch all bookingpositions from db
                    /** @var array<Bookingposition> $bps */
                    $bps = $this->find('\src\models\Bookingposition', 'booking_id', $booking->getId());
                    foreach ($bps as $p) {
                        $param['bookingpositions'][$booking->getId()][] = $p;
                        // fetch all houses from db (if not already present)
                        if (!in_array($p->getHouseId(), $houseIds)) {
                            $result = $this->find('\src\models\House', 'id', $p->getHouseId(), 1);
                            // if house still exists, deliver to frontend
                            if ($result != null) {
                                $param["houses"][$p->getHouseId()] = $result;
                                $houseIds[] = $p->getHouseId();
                            }
                        }
                    }
                    // check that at least one house has been fetched
                    if (empty($param["houses"])) {
                        throw new \Exception("No house loaded");
                    }
                }
            }
        } catch (\Exception $e) {
            $_SESSION['message'] = "Buchungsdaten wurden nicht gefunden";
            redirect('/profile',302);
            die();
        }
        new ViewController('history', $param);
    }



}