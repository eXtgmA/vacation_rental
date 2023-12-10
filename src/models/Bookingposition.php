<?php
namespace src\models;

class Bookingposition extends BaseModel
{
    private int $id;
    private string $dateStart;
    /** @var array<string> */
    private array $priceDetailList;
    private int $houseId;
    private int $bookingId;
    /** @var string[] */
    public array $allowedAttributes = ['data_start', 'date_end', 'price_detail_list', 'house_id', 'booking_id'];
    public static string $table = 'bookingpositions';

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

    public function getDateStart(): string
    {
        return $this->dateStart;
    }

    public function setDateStart(string $dateStart): void
    {
        $this->dateStart = $dateStart;
    }

    /**
     * @return string[]
     */
    public function getPriceDetailList(): array
    {
        return $this->priceDetailList;
    }

    /**
     * @param array<string> $priceDetailList
     * @return void
     */
    public function setPriceDetailList(array $priceDetailList): void
    {
        $this->priceDetailList = $priceDetailList;
    }

    public function getHouseId(): int
    {
        return $this->houseId;
    }

    public function setHouseId(int $houseId): void
    {
        $this->houseId = $houseId;
    }

    public function getBookingId(): int
    {
        return $this->bookingId;
    }

    public function setBookingId(int $bookingId): void
    {
        $this->bookingId = $bookingId;
    }
}
