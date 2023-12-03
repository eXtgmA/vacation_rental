<?php
namespace src\models;

use Exception;
use mysqli_result;

class Option extends BaseModel
{
    private int $id;
    private string $name;
    private string $description;
    private float $price;
    private bool $is_disabled;
    private int $house_id;
    private int $image_id;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieves an Option from the database specified by its index
     *
     * @param int $option_id
     * @return Option|null
     */
    public function getOption(int $option_id): Option|null
    {
        try {
            $query = "select * from options where id = '{$option_id}' limit 1";
            $sql = $this->connection->query($query);
            if ($sql instanceof mysqli_result) {
                $result = $sql->fetch_object('\src\models\Option');
                // if all ok return Option
                if (isset($result) && $result instanceof Option) {
                    return $result;
                // if no Option could be fetched -> throw exception
                } else {
                    error_log('Option with id "' . $option_id . '" could not be fetched.');
                    throw new Exception('Option konnte nicht geladen werden.');
                }
            }
        } catch (Exception $exception) {
            $_SESSION['message'] = $exception->getMessage();
        }
        return null;
    }

    /**
     * @param array<string> $param
     * @return void
     * @throws Exception
     */
    public function addOption(array $param) : void
    {
        // insert option into database
        $query = "insert into option (";
        $i = 1;
        $paramLength=(count($param));
        foreach ($param as $key => $value) {
            if ($i < $paramLength) {
                $query = $query .$key.",";
            } else {
                $query = $query .$key;
            }
            $i++;
        }
        $query = $query . ") Values (";
        $i = 1;
        foreach ($param as $key => $value) {
            if ($i < $paramLength) {
                $query = $query ."'". $value."',";
            } else {
                $query = $query ."'". $value."'";
            }
            $i++;
        }
        $query = $query . ")";
        try {
            $result = $this->connection->query($query);
        } catch (\Exception $e) {
            error_log($e);
            throw new \Exception($e);
        }
        $_SESSION['message'] = 'Option wurde erfolgreich angelegt';

//        header("location: /option/'{$param['id']}'", true, 302);
    }

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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = ($price >= 0) ? $price : 0;
    }

    public function isIsDisabled(): bool
    {
        return $this->is_disabled;
    }

    public function setIsDisabled(bool $is_disabled): void
    {
        $this->is_disabled = $is_disabled;
    }

    public function getHouseId(): int
    {
        return $this->house_id;
    }

    public function getImageId(): int
    {
        return $this->image_id;
    }
}
