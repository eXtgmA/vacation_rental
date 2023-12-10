<?php

namespace src\models;

class House extends BaseModel
{
    private int $id;
    private string $name;
    private string $description;
    private float $price;
    private int $max_person;
    private int $postal_code;
    private string $city;
    private string $street;
    private int $house_number;
    private int $square_meter;
    private int $room_count;
    private bool $is_disabled;
    private int $owner_id;
    public static string $table = 'houses';
    public static array $allowedAttributes = ['name', 'description', 'price', 'max_person', 'postal_code', 'city', 'street', 'house_number', 'square_meter', 'room_count', 'is_disabled','owner_id' ];

    public static array $rules = ['name'=>['string'], 'description'=>['string'], 'price'=>['double'], 'max_person'=>['integer'], 'postal_code'=>['integer'], 'city'=>['string'], 'street'=>['string'], 'house_number'=>['integer'], 'square_meter'=>['integer'], 'room_count'=>['integer'], 'is_disabled'=>['integer'],'owner_id' ];

    private string $frontimage;
    /**
     * @var string[]
     */

    public function __construct($modelData=null)
    {
        if($modelData){
            parent::createFromModelData($modelData);
        }
    }

    public function toggleStatus(): void
    {
//        get old status and invert
        $newStatus = (int)!$this->getIsDisabled();
        $query = "update houses set is_disabled = {$newStatus}  where id = {$this->getId()}";
//        write to db
        $this->connection()->query($query);
    }

    public function getFrontImage(): string
    {
        $result=$this->find('\src\models\Image', 'house_id', $this->id,1);
        if($result) {
            return $this->frontimage = $result->getUuid();
        }
        return $this->frontimage='';
    }

    /**
     * @param int $houseId
     * @param string[] $frontimage
     * @return void
     * @throws \Exception
     */
    public function setFrontimage(int $houseId, array $frontimage): void
    {

        $type = 1;
        $newImage = new Image();
        $this->frontimage = $newImage->postsave($frontimage, $houseId, $type);
    }

    /**
     * @param string[] $param
     * @return string[]
     */
    public function filter(array $param): array
    {
        return array_filter($param, function ($key) {
            return in_array($key, $this->allowedAttributes);
        }, ARRAY_FILTER_USE_KEY);
    }


/**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }


    /**
     * @param $price
     * @return void
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }


    /**
     * @return int
     */
    public function getMaxPerson()
    {
        return $this->max_person;
    }


    /**
     * @param $max_person
     * @return void
     */
    public function setMaxPerson(int $max_person): void
    {
        $this->max_person = $max_person;
    }


    /**
     * @return int
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }


    /**
     * @param $postal_code
     * @return void
     */
    public function setPostalCode(int $postal_code): void
    {
        $this->postal_code = $postal_code;
    }


    /**
     * @return int
     */
    public function getHouseNumber()
    {
        return $this->house_number;
    }


    /**
     * @param $house_number
     * @return void
     */
    public function setHouseNumber(int $house_number): void
    {
        $this->house_number = $house_number;
    }


    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }


    /**
     * @param $city
     * @return void
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }


    /**
     * @return int
     */
    public function getRoomCount()
    {
        return $this->room_count;
    }


    /**
     * @param $room_count
     * @return void
     */
    public function setRoomCount(int $room_count): void
    {
        $this->room_count = $room_count;
    }


    /**
     * @return bool
     */
    public function getIsDisabled()
    {
        return $this->is_disabled;
    }

    /**
     * @param $is_disabled
     * @return void
     */
    public function setIsDisabled(bool $is_disabled): void
    {
        $this->is_disabled = $is_disabled;
    }


    /**
     * @return int
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @param $owner_id
     * @return void
     */
    public function setOwnerId(int $owner_id): void
    {
        $this->owner_id = $owner_id;
    }


    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }


    /**
     * @param $street
     * @return void
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }


    /**
     * @return int
     */
    public function getSquareMeter()
    {
        return $this->square_meter;
    }


    /**
     * @param $square_meter
     * @return void
     */
    public function setSquareMeter(int $square_meter): void
    {
        $this->square_meter = $square_meter;
    }
}
