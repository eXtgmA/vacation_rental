<?php

namespace src\models;

use DateInterval;
use DatePeriod;
use Exception;

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
    /**
     * @var string[]
     */
    public static array $allowedAttributes = ['name', 'description', 'price', 'max_person', 'postal_code', 'city', 'street', 'house_number', 'square_meter', 'room_count', 'is_disabled','owner_id' ];

    /**
     * @var string[]
     */
    public static array $updateableAttributes = ['name', 'description', 'price', 'max_person', 'postal_code', 'city', 'street', 'house_number', 'square_meter', 'room_count', 'is_disabled'];

    /**
     * @var array<int|string, array<int|string>|string>
     */
    public static array $rules = ['name'=>['string'], 'description'=>['string'], 'price'=>['double'], 'max_person'=>['integer'], 'postal_code'=>['integer'], 'city'=>['string'], 'street'=>['string'], 'house_number'=>['integer'], 'square_meter'=>['integer'], 'room_count'=>['integer'], 'is_disabled'=>['integer'],'owner_id' ];

    /**
     * @var string
     */
    private string $frontimage;

    /**
     * @param string[] $modelData
     */
    public function __construct($modelData = null)
    {

        if ($modelData) {
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
        // get front image id from db
        $query = "SELECT id FROM images WHERE house_id={$this->id} && typetable_id=1 LIMIT 1;";
        $result = $this->connection()->query($query);
        if (!($result instanceof \mysqli_result)) {
            return '';
        }
        $row = $result->fetch_assoc();
        // no image found => return empty string
        if ($row == null) {
            return '';
        }
        // fetch associated uuid
        /** @var Image $image */
        $image = $this->find('\src\models\Image', 'id', $row["id"], 1);
        $this->frontimage = $image->getUuid();
        return $this->frontimage;
    }

    public function getLayoutImage(): string
    {
        // get front image id from db
        $query = "SELECT id FROM images WHERE house_id={$this->id} && typetable_id=2 LIMIT 1;";
        $result = $this->connection()->query($query);
        if (!($result instanceof \mysqli_result)) {
            return '';
        }
        $row = $result->fetch_assoc();
        // no image found => return empty string
        if ($row == null) {
            return '';
        }
        // fetch associated uuid
        /** @var Image $image */
        $image = $this->find('\src\models\Image', 'id', $row["id"], 1);
        return $image->getUuid();
    }

    /**
     * Get all optional image objects in array
     *
     * @return array<Image>
     * @throws Exception
     */
    public function getOptionalImages(): array
    {
        // get optional images id from db
        $optionalImages = [];
        $query = "SELECT id FROM images WHERE house_id={$this->id} && typetable_id=4 LIMIT 3;";
        $result = $this->connection()->query($query);
        if (!($result instanceof \mysqli_result)) {
            return [];
        }

        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            // no image found => return empty string
            if ($row == null) {
                return [];
            }
            // fetch associated uuids into array
            /** @var array<Image> $optionalImages */
            $optionalImages[] = $this->find('\src\models\Image', 'id', $row["id"], 3);
        }
        return $optionalImages;
    }

    /**
     * Get all features related to this house
     *
     * @return array<Feature>
     */
    public function getAllFeatures() : array
    {
        // query many-to-many relation in database
        $query = "SELECT features_id FROM houses_has_features WHERE houses_id={$this->id};";
        $result = $this->connection()->query($query);
        if (!($result instanceof \mysqli_result)) {
            return [];
        }
        // append all found house ids to array
        $features = [];
        while ($row = $result->fetch_assoc()) {
            try {
                $features[] = $this->find('src\models\Feature', 'id', $row['features_id'], 1);
            } catch (Exception $e) {
                error_log("Feature ({$row['features_id']}) could not be retrieved because: ". $e);
                continue;
            }
        }
        return $features;
    }

    /**
     * Reset related features to none
     *
     * @return void
     */
    public function resetRelatedFeatures() : void
    {
        $query = "DELETE FROM houses_has_features WHERE houses_id={$this->id};";
        $this->connection()->query($query);
    }

    /**
     * return all tags as an array of strings
     *
     * @return array<string>
     */
    public function getTags(): array
    {
        // Initialize an empty array to hold the tags
        $tags = [];

        // Write the SQL query
        $query = "SELECT tags.* FROM tags WHERE house_id = {$this->id}";

        // Execute the query
        $result = $this->connection()->query($query);

        // Check if the query was successful
        if ($result instanceof \mysqli_result) {
            // Fetch the tags and add them to the array
            while ($row = $result->fetch_assoc()) {
                $tags[] = $row['name'];
            }
        }
        return $tags;
    }

    /**
     * @param int $houseId
     * @param string[] $frontimage
     * @return void
     * @throws Exception
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
     * Get all options associated with this house
     *
     * Returns false if no options found
     *
     * @return array<Option>|false
     */
    public function getAllOptions() : array|false
    {
        try {
            /** @var Option[] $options */
            $options = $this->find('\src\models\Option', 'house_id', $this->id);
        } catch (Exception $e) {
            $_SESSION['message'] = "Keine Optionen vorhanden";
            return false;
        }
        return $options;
    }


    /**
     * Delete a house and all of its options and images
     *
     * @return bool
     * @throws Exception
     */
    public function deleteHouse() : bool
    {
        $this->connection()->begin_transaction();
        // get all options and images
        $allOptions = $this->getAllOptions();
        $allImages = $this->getImages();
        try {
            // delete all options (related option images included)
            if ($allOptions) {
                foreach ($allOptions as $option) {
                    $option->deleteOption();
                }
            }

            // delete all images (front, layout and other)
            if ($allImages) {
                foreach ($allImages as $image) {
                    $imgPath = $image->deleteImage();
                    if (!unlink($imgPath)) {
                        error_log("Image could not be deleted (unlinked) from disk (path: {$imgPath} )");
                    }
                }
            }

            // reset related features
            $this->resetRelatedFeatures();

            // todo : delete related tags

            // delete house itself
            $this->delete(model: 'House', id: $this->id);

            // if all ok, commit to db
            $this->connection()->commit();
        } catch (Exception $e) {
            $this->connection()->rollback();
            $_SESSION['message'] = "Haus konnte nicht gelöscht werden";
            throw new Exception($e);
        }
        $_SESSION['message'] = "Haus erfolgreich gelöscht";
        return true;
    }

    /**
     * Get all images associated with this house
     *
     * Returns false if no image found
     *
     * @return array<Image>|false
     */
    public function getImages() : array|false
    {
        try {
            /** @var Image[] $images */
            $images = $this->find('\src\models\Image', 'house_id', $this->id);
        } catch (Exception $e) {
            $_SESSION['message'] = "Keine Fotos vorhanden";
            return false;
        }
        return $images;
    }

    /**
     * Get all booked dates as json encoded string
     *
     * @return string
     */
    public function getBookedDates() : string
    {
        $query = "SELECT date_start, date_end FROM bookingpositions WHERE house_id={$this->id} AND booking_id IN (SELECT id FROM bookings b WHERE b.is_confirmed = 1)";
        $result = $this->connection()->query($query);
        $dates = [];
        if ($result instanceof \mysqli_result) {
            while ($row = $result->fetch_assoc()) {
                // get start and end date
                $date_start = date_create($row['date_start']);
                $date_end = date_create($row['date_end']);
                if ($date_start && $date_end) {
                    // make a period of time between those days
                    $interval = DateInterval::createFromDateString('1 day');
                    $daterange = new DatePeriod($date_start, $interval, $date_end);
                    // append every day of the period to the array $bookedDays
                    foreach ($daterange as $date) {
                        $dates[] = $date->format('Y-m-d');
                    }
                    // include end date
                    $dates[] = $date_end->format('Y-m-d');
                }
            }
        }
        // return json string with content or an empty string
        return (!empty($dates)) ? (json_encode($dates) ?: "") : "";
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
