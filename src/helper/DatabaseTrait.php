<?php

namespace src\helper;




use Exception;

trait DatabaseTrait
{

    public function connection() :\mysqli
    {
        $connection= \src\config\getConnection();
        return $connection;
    }


    /**
     * @param string $query
     * @return \mysqli_result
     * @throws Exception
     */
    public function runQuery($query) : \mysqli_result
    {
        $connection = $this->connection();
        $result = $connection->query($query);
        if ($result === false) {
            throw new Exception('Query failed: ' . $connection->error);
        }
        if ($result instanceof \mysqli_result) {
            return $result; // return if valid
        }
        throw new Exception('Kein gültiges Mysqli Result'); //
    }

}