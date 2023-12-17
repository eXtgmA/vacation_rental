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
     * @param $model
     * @param $column
     * @param $identifier
     * @param null|int $limit
     * @throws Exception
     */
    public function find($model, $column, $identifier, $limit = null) //@phpstan-ignore-line
    {
//        $model = '\src\models\\' . $model;
        // switch between string or int atm no boolcheck
        $identifierString = "'" . $identifier . "'";
        if (is_numeric($identifier)) {
            $identifierString = $identifier;
        }
        $table = $model::$table;

        if ($limit) {
            $query = "Select * from {$table} where $column = {$identifierString} limit {$limit}";
        } else {
            $query = "Select * from {$table} where $column = {$identifierString}";
        }
        $connection = $this->connection();
        $result = $connection->query($query);

        if ($result === false) {
            throw new Exception('Query failed: ' . $connection->error);
        }
        $objectArray = [];
        if ($result instanceof \mysqli_result) {
            if ($limit) {
                return $object = $result->fetch_object($model);
            }
            while ($object = $result->fetch_object($model)) {
                $objectArray[] = $object;
            }
            return $objectArray;
        }
        throw new Exception('Kein gültiges Mysqli Result');
    }

    /**
     * @return void
     */
    public function save(): void
    {
        try {
            // prepare query
            $query = $this->buildInsertQuery($this);
            // $connection=$this->connection()->query($query);
            $con = $this->connection();
            if ($con->query($query) === true) {
                $id = $con->insert_id;
                $this->setId($id);//@phpstan-ignore-line
            } else {
                throw new Exception('Etwas lief bei dem Speichern in der Datenbank schief');
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }


    /**
     * @param mixed $objectData
     * @return string
     */
    public function buildInsertQuery($objectData): string
    {
        $table = get_class($this)::$table;
        $attributes = $this->objectToArray();
//          "," connect all attributes and values
        $columns = implode(',', array_keys($attributes));
//        transform bool
        foreach ($attributes as $key => $value) {
            if (is_bool($value)) {
                $attributes[$key] = (int)$value;
            }
        }
        $values = "'" . implode("','", array_values($attributes)) . "'";
        $values = str_replace(",'',", ",null,", $values);
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
     * @return array<mixed>
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

    public function delete(string $model, int $id): void
    {
        try {
            $model = '\src\models\\' . $model;
            $table = $model::$table;
            $connection = $this->connection();
            $query = "delete from {$table} where id={$id}";
            $connection->query($query);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    /**
     * Update attributes that are allowed for updating
     *
     * All other attributes are ignored (even misspelled ones)
     *
     * @param array<string> $param
     * @return void
     */
    public function update($param): void
    {
        try {
            //$this is our current object
            $class = get_Class($this);
            $updateableAttributes = $class::$updateableAttributes;
            $table = $class::$table;
            $updateables = $this->filterUpdateableAttributes($param, $updateableAttributes);

//            @phpstan-ignore-next-line   cant resolve specific object in base class when called
            $query = $this->prepareUpdateQuery($table, $this->getId(), $updateables);
            $this->connection()->query($query);
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }

    /**
     * @param array<string> $param
     * @param array<string> $updateableAttributes
     * @return array<string>
     */
    public function filterUpdateableAttributes($param, $updateableAttributes): array
    {
        // filter all attributes allowed for update
        $updateables = [];
        foreach ($param as $updatekey => $updatevalue) {
            foreach ($updateableAttributes as $attr) {
                if ($updatekey === $attr) {
                    // if update allowed => write to
                    $updateables[$updatekey] = $updatevalue;
                    break;
                }
            }
        }
        return $updateables;
    }

    /**
     * @param string $table
     * @param int $id
     * @param array<string> $updateables
     * @return string
     */
    public function prepareUpdateQuery($table, $id, $updateables): string
    {
        $query = "update {$table} set ";
        foreach ($updateables as $key => $value) {
            $query = $query . $key . " = '{$value}' ,";
        }
        $query = rtrim($query, ',');
        $query = $query . " where id = {$id} limit 1";
        return $query;
    }
}
