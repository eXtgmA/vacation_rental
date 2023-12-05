<?php

namespace src\helper;


use Exception;
use MongoDB\Driver\Query;

trait DatabaseTrait
{

    public function connection(): \mysqli
    {
        $connection = \src\config\getConnection();
        return $connection;
    }


    /**
     * @param string $query
     * @return \mysqli_result
     * @throws Exception
     */
    public function fetch($query): \mysqli_result
    {
        $connection = $this->connection();
        $result = $connection->query($query);
        if ($result === false) {
            throw new Exception('Query failed: ' . $connection->error);
        }
        if ($result instanceof \mysqli_result) {
            return $result; // return if valid
        }
        throw new Exception('Kein gültiges Mysqli Result');
    }

    /**
     * @param $query
     * @return bool
     * @throws Exception
     */
    public function store($query): bool
    {
        $connection = $this->connection();
        try {
            $result = $connection->query($query);
        } catch (Exception $exception) {
            throw new Exception('Daten konnten nicht gespeichert werden ' . $exception);
        }
        return $result;
    }

    public function storeAndReturn($query,$model,$table)
    {
        //store
        $connection = $this->connection();
        try {
            $result = $connection->query($query);
        } catch (Exception $exception) {
            throw new Exception('Daten konnten nicht gespeichert werden ' . $exception);
        }
        // get
        $id=$connection->insert_id;
        $query = "Select * from {$table} where id = {$id} limit 1";
        $result = $this->fetch($query);
        while ($object=$result->fetch_object($model)){
            return $object;
        }
    }

}