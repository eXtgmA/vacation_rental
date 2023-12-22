<?php

namespace src\models;

class Image extends BaseModel
{
    private int $id;
    private string $uuid;
    private int $house_id;
    private int|null $typetable_id;
    /**
     * @var array|string[]
     */
    public static array $allowedAttributes = ['uuid','house_id','typetable_id'];

    /**
     * @var array|string[]
     */
    public static array $updateableAttributes = ['uuid','typetable_id'];

    /**
     * @var string
     */
    public static string $table = 'images';

    /**
     * @param array<int|string> $modelData
     */
    public function __construct($modelData = null)
    {
        if ($modelData) {
            parent::createFromModelData($modelData);
        }
    }

    /**
     * @param string[] $file
     * @return string
     * @throws \Exception
     */
    public static function imageToDisk(array $file): string
    {


        if ($file['tmp_name'] == "") {
            error_log("Tried to save an image, that does not exist (no temp_name found)");
            throw new \Exception("Hochladen der Bilder fehlgeschlagen");
        }
        // create random binary string in length of 40 Chars -> translate to HEX
        $randomId = bin2hex(random_bytes(15));
        // Uploaded file is in "tmp_name" (php standard)
        $image = $file['tmp_name'];
        // get the uploaded file extension
        $mimetype = mime_content_type($image);
        $extension = '';
        if ($mimetype) {
            $exploded = explode('/', $mimetype);
            if (count($exploded) == 2) {
                $extension = $exploded[1];
            }
        } else {
            error_log("Image could not be saved, because mimetype was not correct");
            throw new \Exception('Bilderdaten nicht korrekt');
        }
        // creating saving path
        $path = __DIR__ . "/../../public/images/";
        // putting path and storage name together
        $imageName = $randomId . "." . $extension;
        try {
            // saving process
            move_uploaded_file($image, $path . $imageName);
        } catch (\Exception $e) {
            var_dump($e);
        }
        return $imageName;
    }

    /**
     * @param string[] $file
     * @param int $houseId
     * @param int|null $typeId
     * @return string
     * @throws \Exception
     */
    public function postsave(array $file, int $houseId, int $typeId = null,): string
    {
        if ($file['tmp_name'] == "") {
            header('location: /image', true, 302);
        }
        // create random binary string in length of 40 Chars -> translate to HEX
        $randomId = bin2hex(random_bytes(15));
        // Uploaded file is in "tmp_name" (php standard)
        $image = $file['tmp_name'];
        // get the uploaded file extension
        $mimetype = mime_content_type($image);
        $extension = '';
        if ($mimetype) {
            $exploded = explode('/', $mimetype);
            if (count($exploded) == 2) {
                $extension = $exploded[1];
            }
        } else {
            throw new \Exception('Bilderdaten nicht korrekt');
        }
        // creating saving path
        $path = __DIR__ . "/../../public/images/";
        // putting path and storage name together
        $imageName = $randomId . "." . $extension;
        try {
            // saving process
            move_uploaded_file($image, $path . $imageName);
            // save to db
            $query = ("insert into images (uuId,house_id, typetable_id) values('{$imageName}',{$houseId},{$typeId}) ");

//            $this->store($query);
        } catch (\Exception $e) {
            var_dump($e);
        }
        return $imageName;
    }

    /**
     * @param array<string> $file
     * @return bool
     * @throws \Exception
     */
    public function updateImage(array $file) : bool
    {
        $path = __DIR__ . "/../../public/images/" . $this->uuid;
        // delete image from disk
        if (file_exists($path)) {
            unlink($path);
        }

        // save new image to disk
        try {
            $this->uuid = Image::imageToDisk($file);
        } catch (\Exception $e) {
            error_log("Image could not be saved to disk, because:\n" . $e);
            throw new \Exception($e);
        }

        // update db entry with new uuid
        $param['uuid'] = $this->uuid;
        $this->update($param);

        return true;
    }

    /**
     * Delete an image from database
     *
     * Returns path of image for manual deletion from disk
     *
     * @return string
     * @throws \Exception
     */
    public function deleteImage(): string
    {
        // delete image from disk
        $path = __DIR__ . "/../../public/images/" . $this->uuid;
        try {
            // delete image from database
            $this->delete('Image', $this->id);
        } catch (\Exception $e) {
            error_log("Error while deleting image ({$this->id}) from database.");
            throw new \Exception("Image konnte nicht von der Datenbank gelöscht werden");
        }
        return $path;
        // image has to be removed from disk by the caller using the returned path
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getHouseId(): int
    {
        return $this->house_id;
    }

    public function setHouseId(int $house_id): void
    {
        $this->house_id = $house_id;
    }

    public function getTypetableId(): ?int
    {
        return $this->typetable_id;
    }

    public function setTypetableId(?int $typetable_id): void
    {
        $this->typetable_id = $typetable_id;
    }
}
