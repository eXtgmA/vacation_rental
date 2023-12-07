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
    private int $house_id;  /* @phpstan-ignore-line */
    private int $image_id;  /* @phpstan-ignore-line */


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieve an option from the database specified by its index
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
     * Add an option to the database.
     * In associative array $param, $key must be equal to column name in database table
     *
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
            $this->connection->begin_transaction();
            $result = $this->connection->query($query);
            // add option name to tags
            $query = "insert into tags (name) values ('".$param['name']."'');";
            $result = $this->connection->query($query);
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollback();
            error_log($e);
            throw new \Exception($e);
        }
        $_SESSION['message'] = 'Option wurde erfolgreich angelegt';

//        header("location: /option/'{$param['id']}'", true, 302);
    }

    /**
     * Update the option defined by $id with data provided by $param
     * In associative array $param, $key must be equal to column name in database table
     *
     * @param int $id
     * @param array<string> $param
     * @return void
     * @throws Exception
     */
    public function updateOption(int $id, array $param) : void
    {
        // update option
        $query = "UPDATE options SET ";
        $i = 1;
        $paramLength=(count($param));
        foreach ($param as $key => $value) {
            if ($i < $paramLength) {
                $query = $query .$key."=".$value.",";
            } else {
                $query = $query .$key."=".$value;
            }
            $i++;
        }
        $query = $query . " WHERE id=".$id." LIMIT 1;";
        try {
            $this->connection->begin_transaction();
            $result = $this->connection->query($query);
            // update tag if name changed
            if (isset($param["name"]) && ($param["name"] != $this->name)) {
                $tag_query = "UPDATE tags SET name='".$param["name"]."' WHERE name='".$this->name."' LIMIT 1;";
                $this->connection->query($tag_query);
            }
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollback();
            error_log($e);
            throw new \Exception($e);
        }
        $_SESSION['message'] = 'Option wurde erfolgreich aktualisiert';

//        header("location: /optionedit/'{$param['id']}'", true, 302);
    }

    /**
     * Delete an option and the related image from database.
     * Either returns true or throws exception.
     *
     * @param int $option_id
     * @param int $image_id
     * @return bool
     * @throws Exception
     */
    public function deleteOption(int $option_id, int $image_id) : bool
    {
        $this->connection->begin_transaction();
        try {
            // first: delete option
            $query = "DELETE FROM options WHERE id=".$option_id." LIMIT 1;";
            $this->connection->query($query);
            // second: delete image
//            $image = new \src\models\Image();     // todo: activate if delete-function in Image model exists
//            $image->deleteImage($image_id);
        } catch (Exception $e) {
            $this->connection->rollback();
            error_log("Error while deleting option (" .$option_id. ") from databse.");
            throw new Exception($e);
        }
        // if all ok, commit transaction
        $this->connection->commit();
        $_SESSION["message"] = "Option wurde erfolgreich gelöscht";
        return true;
        // redirection to next page has to be executed by caller
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
