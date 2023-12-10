<?php
namespace src\models;

class Booking extends BaseModel
{
    private int $id;
    private bool $isConfirmed;
    private string $bookedAt;
    private int $userId;
    /**
     * @var string[]
     */
    public array $allowedAttributes = ['is_confirmed', 'booked_at', 'user_id'];
    public static string $table = 'bookings';

    public function __construct()
    {
        parent::__construct();
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
        return $this->isConfirmed;
    }

    public function setIsConfirmed(bool $isConfirmed): void
    {
        $this->isConfirmed = $isConfirmed;
    }

    public function getBookedAt(): string
    {
        return $this->bookedAt;
    }

    public function setBookedAt(string $bookedAt): void
    {
        $this->bookedAt = $bookedAt;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
}
