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
        // prepare statement
        $query = "insert into houses ( owner,";
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
            var_dump($e);
        }
    }
}
