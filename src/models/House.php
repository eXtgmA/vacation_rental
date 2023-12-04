<?php
namespace src\models;

class House extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array<string> $param
     * @return void
     */
    public function addhouse(array $param): void
    {
        // remove all unnecessary keys, only allow the keys from db table houses
        $param = array_filter($param, function ($key) {
            return in_array($key, ['name', 'description', 'price', 'max_person', 'postal_code', 'city', 'street', 'house_number', 'square_meter', 'room_count', 'is_disabled']);
        }, ARRAY_FILTER_USE_KEY);

        // prepare statement
        $query = "insert into houses ( owner_id,";
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
        $query = $query . ") Values ( '{$_SESSION['user']}',";
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
        $_SESSION['message'] = 'Haus wurde erfolgreich angelegt';

        header("location: /offer", true, 302);
    }
}
