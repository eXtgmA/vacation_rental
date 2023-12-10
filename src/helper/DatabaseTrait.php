<?php

namespace src\helper;

use Exception;

trait DatabaseTrait
{
    public function connection(): \mysqli
    {
        $connection = \src\config\getConnection();
        return $connection;
    }

    /**
     * @param string $query
     * @throws Exception
     */
    public function find($model,$column,$identifier,$limit=null)
    {
//        $model = '\src\models\\' . $model;
        // switch between string or int atm no boolcheck
        $identifierString = "'" . $identifier . "'";
        if(is_numeric($identifier)){
            $identifierString = $identifier;
        }
        $table=$model::$table;

        if($limit){
            $query = "Select * from {$table} where $column = {$identifierString} limit {$limit}";
        }else{
            $query = "Select * from {$table} where $column = {$identifierString}";
        }
        $connection = $this->connection();
        $result = $connection->query($query);

        if ($result === false) {
            throw new Exception('Query failed: ' . $connection->error);
        }
        $objectArray = [];
        if ($result instanceof \mysqli_result) {
            if($limit){
                return $object = $result->fetch_object($model);
            }
            while($object=$result->fetch_object($model)){
                $objectArray[] = $object;
            }
            return $objectArray;
        }
        throw new Exception('Kein gültiges Mysqli Result');
    }

    public function save()
    {
        // prepare query
        $query = $this->buildInsertQuery($this);
        // $connection=$this->connection()->query($query);
        $con = $this->connection();
        if ($con->query($query) === TRUE) {
            $id = $con->insert_id;
        }
        $this->setId($id);
    }

    public function buildInsertQuery($objectData)
    {
        $table = get_class($this)::$table;
        $attributes = $this->objectToArray();
//          "," connect all attributes and values
        $columns = implode(',', array_keys($attributes));
//        transform bool
        foreach ($attributes as $key=>$value){
            if(is_bool($value)){
               $attributes[$key]=(int)$value;
            }
        }
        $values = "'" . implode("','", array_values($attributes)) . "'";
//          insert into prebuild query
        $query = "insert into $table($columns) values($values)";
        $query = rtrim($query, ','); // Remove trailing comma
        return $query;

    }

    /**
     * Converting an Object to array
     * Here we have the Oject Atribute as Key
     * and the belonging data as value
     *
     * Because the class Key is build like "namespace\classname\attribute"
     * we have to cut off the class name
     * ! the extracted string contains /0 stopbits, which have to be removed too !
     *
     * @return array
     */
    public function objectToArray(): array
    {
//        Turn object into array
        $arrifiedModel = (array)$this;
//        initializing our resultArray which will store our completely converted result
        $attributeArray = [];
        $class = get_class($this);
        foreach ($arrifiedModel as $key => $value) {
//            remove classname from key
            $replaced = (str_replace($class, '', $key));
//            remove stopbits
            $replaced = str_replace("\0", '', $replaced);
            $attributeArray[$replaced] = $value;
        }
        return $attributeArray;
    }
}
