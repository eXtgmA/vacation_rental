<?php
namespace src\models;

use Exception;

class Option extends BaseModel
{
    private int $id;
    private string $name;
    private string $description;
    private float $price;
    private bool $is_disabled;
    private int $house_id;
    private int $image_id;

    public static string $table = 'options';

    /**
     * @var array<string, array<int, string>>
     */
    public static array $rules = ['name'=>['string'], 'description'=>['string'], 'price'=>['double'], 'is_disabled'=>['integer'],'house_id'=>['integer'],'image_id'=>['integer'] ];

    /**
     * @var string[]
     */
    public static array $allowedAttributes = ['name', 'description', 'price', 'is_disabled', 'house_id', 'image_id'];

    /**
     * @var string[]
     */
    public static array $updateableAttributes = ['name', 'description', 'price', 'is_disabled'];

    public function __construct($modelData = null)
    {
        if ($modelData) {
            parent::__construct($modelData);
        }
    }

    /**
     * Delete an option and the related image from database and disk.
     *
     * Either returns true or throws exception.
     *
     * @return bool
     * @throws Exception
     */
    public function deleteOption() : bool
    {
        $this->connection()->begin_transaction();
        try {
            // first: delete option
            $this->delete(model: 'Option', id: $this->id);
            // second: delete image
            /** @var Image $image */
            $image = $this->find('\src\models\Image', 'id', $this->image_id, 1);
            $imgPath = $image->deleteImage();
            // remove image from disk
            if (!unlink($imgPath)) {
                error_log("Image could not be deleted (unlinked) from disk (path: {$imgPath} )");
            }
            // if all ok, commit transaction
            $this->connection()->commit();
        } catch (Exception $e) {
            // if error, rollback
            $this->connection()->rollback();
            error_log("Error while deleting option ({$this->id}) from databse.");
            $_SESSION["message"] = "Option konnte nicht gelöscht werden";
            throw new Exception($e);
        }
        $_SESSION["message"] = "Option wurde erfolgreich gelöscht";
        return true;
        // redirection to next page has to be executed by caller
    }

//    /**
//     * @param int $house_id
//     * @param array<string> $image
//     * @return int
//     * @throws Exception
//     */
//    public function setOptionimage(int $house_id, array $image) : int
//    {
//        $typetable_id = 3;
//        $newImage = new Image();
//        $imagename = $newImage->postsave($image, $house_id, $typetable_id);
//         get image id via uuid from database
//        $query = "SELECT id FROM images WHERE uuid='{$imagename}' LIMIT 1;";
//        try {
//            $result = $this->fetch($query);
//        } catch (\Exception $e) {
//            error_log($e);
//            redirect("/option/create/".$house_id, 302);
//            die();
//        }
//        $row = $result->fetch_assoc();
//        if ($row === null) {
//            $_SESSION['message']="Das gespeicherte Foto ist wohl verlorengegangen... Hier ist etwas schiefgegangen.";
//            redirect("/option/create/".$house_id, 302);
//            die();
//        } else {
//            return (int)$row['id'];
//        }
//    }

    /**
     * Toggle the status of an option
     *
     * @return void
     */
    public function toggleStatus(): void
    {
        // get old status and invert
        $newStatus = (int)!$this->isDisabled();
        $query = "UPDATE options SET is_disabled = {$newStatus} WHERE id = {$this->getId()}";
        $result = $this->connection()->query($query);
        if (!$result) {
            error_log("database update error: Status of option ({$this->getId()}) could not be toggled");
        }
    }

    public function getOptionImage(): string
    {
//        todo check if try catch mybe neccessary
        $image = $this->find('\src\models\Image', 'id', $this->image_id, 1);
        return $image->getUuid();
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

    public function isDisabled(): bool
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

    /**
     * @param int $id
     * @return int
     */
    public function setHouseId($id = null): int
    {
        if ($id) {
            $this->house_id = $id;
        }
        return $this->house_id;
    }

    public function getImageId(): int
    {
        return $this->image_id;
    }

    public function setImageId(int $image_id): void
    {
        $this->image_id = $image_id;
    }
}
