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
            // get the one and only booking where "is_confirmed" equals false belonging to current user
            $query = "select * from bookings where user_id = {$_SESSION['user']} and is_confirmed = 0 limit 1";
            $sql=$this->connection()->query($query);
            $booking = null; // fallback initializing
            if ($sql instanceof \mysqli_result) {
                $booking= $sql->fetch_object('\src\models\Booking');
            }
            $param['booking'] = $booking;

            /** @var Booking $booking */
            if ($booking != null) {
                // get all bookingpositions related to this booking
                $param["bookingpositions"] = $booking->getAllBookingpositions();
                // recalculate positions
                if($param['bookingpositions']!=false){

                    $this-> recalculate($param['bookingpositions']);
                }
                $param["bookingpositions"] = $booking->getAllBookingpositions(); // fetch again fresh from db

                // if no positions found show empty cart
                if ($param["bookingpositions"] == false) {
                    new ViewController('cart');
                    die();
                }

                $_SESSION['availabilityError'] = [];

                // get all houses related to all bookingpositions
                $houseIds = [];
                foreach ($param["bookingpositions"] as $bp) {
                    // fetch house if not already loaded
                    $house = null;
                    if (!in_array($bp->getHouseId(), $houseIds)) {
                        $house = $this->find('\src\models\House', 'id', $bp->getHouseId(), 1);
                        $param["houses"][$bp->getHouseId()] = $house;
                        $houseIds[] = $bp->getHouseId();
                    } else {
                        $house = $param["houses"][$bp->getHouseId()]; // @phpstan-ignore-line
                    }
                    // check if booking is still possible
                    if (!$house->isTimeFrameAvailable($bp->getDateStart(), $bp->getDateEnd()) || $house->getIsDisabled()) {
                        // if booking is not available anymore => let user know about it
                        $_SESSION['availabilityError'][] = $bp->getId();
                    }
                }

                // check that at least one house has been fetched
                if (empty($param["houses"])) {
                    error_log("While loading the cart (booking position id {$booking->getId()}) a house was expected to load, but no house found.");
                    throw new \Exception("No house loaded");
                }
            }
        } catch (\Exception $e) {
            error_log("Cart could not be filled with data. Internal error.");
            $_SESSION['message'] = "Ein unerwarteter Fehler ist aufgetreten";
            new ViewController('cart');
            die();
        }
        new ViewController('cart', $param);
    }

    /**
     * @param array<Bookingposition> $bookingpostitions
     * @return void
     * @throws \Exception
     */
    private function recalculate($bookingpostitions):void
    {
        foreach ($bookingpostitions as $position){ // check each house in cart

            $priceList=(json_decode($position->getPriceDetailList(),true)); // get db pricelist
            $house = $this->find('\src\models\House', 'id', $position->getHouseId(), 1); // fetch house
            $newPricePerNight = $house->getPrice(); // get present price per night
            $houseOptions = $house->getAllOptions(); // fetch all options
            $nightCount = $priceList['night_count']; // get db value for Night count //@phpstan-ignore-line
            $recalculatedOptions = []; // check every option for a new price / disabled / deleted // todo delete disabled
            $alloptionsPrice = 0; // get sum of all options in one position
            $options = $priceList['options']; //@phpstan-ignore-line
            /** @var array<int> $options */
            foreach ($options as $optionName=> $optionPrice){
                foreach ($houseOptions as $houseOption){  // compare every booked option with available options // todo change to ID
                    // if an option is deleted it wont be find and so be removed from new pricelist

                    // skip disabled so they wont be part of result
                    if(!$houseOption->isDisabled()){

                    // todo when someone renames an option we cant find it here anymore
                    if($houseOption->getName()==$optionName){
                        $recalculatedOptions[$optionName] = $houseOption->getPrice(); // result is an array of name and price
                        $alloptionsPrice += $recalculatedOptions[$optionName];
                        break; // stop inner loop on first hit
                    }
                    }

                }

            }
            $newList = [];
            $newList['options'] = $recalculatedOptions;
            $newList['price_per_night'] = $newPricePerNight;
            $newList['night_count'] = $nightCount;
            $newList['total_price'] = $alloptionsPrice + ($nightCount * $newPricePerNight);
            // recalculate totalprice
            $updateValues['price_detail_list'] = json_encode($newList);
            $position->update($updateValues); //@phpstan-ignore-line
        }

    }
}
