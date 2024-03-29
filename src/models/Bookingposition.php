<?php
namespace src\models;

class Bookingposition extends BaseModel
{
    private int $id;

    private string $date_start;
    private string $date_end;
    private string $price_detail_list;
    private int $house_id;
    private int $booking_id;
    /** @var string[] */
    public static array $allowedAttributes = ['date_start', 'date_end', 'price_detail_list', 'house_id', 'booking_id'];
    /**
     * @var array|string[]
     */
    public static array $updateableAttributes = ['date_start', 'date_end', 'price_detail_list', 'house_id', 'booking_id'];

    public static string $table = 'bookingpositions';

    public function __construct($modelData = null)
    {
        if ($modelData) {
            parent::__construct($modelData);
        }
    }

    /**
     * Delete a bookingposition from database
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteBookingposition() : bool
    {
        try {
            $this->delete('Bookingposition', $this->id);
        } catch (\Exception $e) {
            error_log("Error while deleting bookingposition ({$this->id}) from databse.");
            throw new \Exception($e);
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

    public function getDateStart(): string
    {
        return $this->date_start;
    }

    public function setDateStart(string $dateStart): void
    {
        $this->date_start = $dateStart;
    }

    /**
     * @return string
     */
    public function getPriceDetailList(): string
    {
        return $this->price_detail_list;
    }

    /**
     * @param string $priceDetailList
     * @return void
     */
    public function setPriceDetailList(string $priceDetailList): void
    {
        $this->price_detail_list = $priceDetailList;
    }

    public function getHouseId(): int
    {
        return $this->house_id;
    }

    public function setHouseId(int $houseId): void
    {
        $this->house_id = $houseId;
    }

    public function getBookingId(): int
    {
        return $this->booking_id;
    }

    public function setBookingId(int $bookingId): void
    {
        $this->booking_id = $bookingId;
    }

    public function getDateEnd(): string
    {
        return $this->date_end;
    }

    public function setDateEnd(string $date_end): void
    {
        $this->date_end = $date_end;
    }
}
