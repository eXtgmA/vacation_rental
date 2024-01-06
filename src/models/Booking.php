<?php
namespace src\models;

class Booking extends BaseModel
{
    private int $id;
    private bool $is_confirmed;
    private string|null $booked_at;
    private int $user_id;
    /**
     * @var string[]
     */
    public static array $allowedAttributes = ['is_confirmed', 'booked_at', 'user_id'];
    public static string $table = 'bookings';

    public function __construct($modelData = null)
    {
        if ($modelData) {
            parent::createFromModelData($modelData);
        }
    }

    /**
     * @return array<Bookingposition>|false
     */
    public function getAllBookingpositions() : array|false
    {
        try {
            return $this->find('\src\models\Bookingposition', 'booking_id', $this->id);
        } catch (\Exception $e) {
            // if error in find queue
            return false;
        }
    }

    /**
     * Check all bookingpositions to a given house if time frame is already reserved in cart or not (start and end date inclusive)
     *
     * @param string $start
     * @param string $end
     * @param int $houseId
     * @return bool
     */
    public function isTimeFrameAvailableInCart(string $start, string $end, int $houseId) : bool
    {
        $bps = $this->getAllBookingpositions();
        if (!empty($bps)) {
            foreach ($bps as $p) {
                if ($houseId == $p->getHouseId()) {
                    $dateStartNew = date_create($start);
                    $dateEndNew = date_create($end);
                    $dateStartOld = date_create($p->getDateStart());
                    $dateEndOld = date_create($p->getDateEnd());
                    if ($dateStartNew && $dateEndNew && $dateStartOld && $dateEndOld) { // check if dates correctly initialized (for phpstan)
                        // not available if time periods are overlapping
                        if ($dateStartNew <= $dateEndOld && $dateEndNew >= $dateStartOld) {
                            return false;
                        }
                    } else {
                        // unexpected error (dates of booking contain bad data)
                        return false;
                    }
                }
            }
        }
        return true;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function isConfirmed(): bool
    {
        return $this->is_confirmed;
    }

    public function setIsConfirmed(bool $isConfirmed): void
    {
        $this->is_confirmed = $isConfirmed;
    }

    public function getBookedAt(): string | null
    {
        return $this->booked_at;
    }

    public function setBookedAt(string $bookedAt = null): void
    {
        $this->booked_at = $bookedAt;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $userId): void
    {
        $this->user_id = $userId;
    }
}
