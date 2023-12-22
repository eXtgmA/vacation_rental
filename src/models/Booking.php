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

    public function getBookedAt(): string | null    // todo: should this be nullable?
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
