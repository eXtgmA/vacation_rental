<?php

namespace src\models;

class Image extends BaseModel
{
    private int $id;

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

    private string $uuid;
    private int $house_id;
    private int|null $typetable_id;
    /**
     * @var array|string[]
     * @phpstan-ignore-next-line
     */
    private array $allowedAttributes = ['uuid','house_id','typetable_id'];


    public function __construct()
    {
        parent::__construct();
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

            $this->store($query);
        } catch (\Exception $e) {
            var_dump($e);
        }
        return $imageName;
    }

    /**
     * Deleting image from disk and DB
     *
     * @return void
     */
    public function postdelete(): void
    {
        $path = __DIR__ . "/../../public/images/" . $_POST['uuid'];
        $deleted = unlink($path);
        $query = "delete from images where uuID like '{$_POST['uuid']}'";
        $this->fetch($query);
        header('location: /image', true, 302);
    }
}