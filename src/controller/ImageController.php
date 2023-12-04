<?php
namespace src\controller;

use PHPStan\Type\Php\VersionCompareFunctionDynamicReturnTypeExtension;
use function _PHPStan_758e5f118\RingCentral\Psr7\str;

class ImageController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getform(mixed $formdata = null): void
    {
        // getting all images
        $query="Select uuID from images";
        $result=$this->connection->query($query);
        $fetchedImagesArray = [];
        if ($result && $result instanceof \mysqli_result) {
            while ($image=$result->fetch_row()) {
                $fetchedImagesArray[] = $image;
            }
        }
        new ViewController('imageform', $fetchedImagesArray);
    }


    /**
     * @param array $file
     * @param int $houseId
     * @param int|null $typeId
     * @return string
     * @throws \Exception
     */
    public function postsave(array $file, int $houseId, int $typeId=null,):string
    {
        if ($file['tmp_name']=="") {
            header('location: /image', true, 302);
        }

        // create random binary string in length of 40 Chars -> translate to HEX
        $randomId=bin2hex(random_bytes(15));
        // Uploaded file is in "tmp_name" (php standard)
        $image = $file['tmp_name'];
        // get the uploaded file extension
        $mimetype = mime_content_type($image);
        $extension = '';
        if ($mimetype) {
            $exploded = explode('/', $mimetype);
            if (count($exploded)==2) {
                $extension=$exploded[1];
            }
        }else{
            throw new \Exception();

        }
        // creating saving path
        $path=__DIR__."/../../public/images/";

        // putting path and storage name together
            $imageName =$randomId . "." . $extension;
        try {
            // saving process
            move_uploaded_file($image, $path.$imageName);
            // save to db
            $query = ("insert into images (uuId,house_id, typetable_id) values('{$imageName}',{$houseId},{$typeId}) ");
            $this->connection->query($query);
            return $imageName;
        } catch (\Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Deleting image from disk and DB
     *
     * @return void
     */
    public function postdelete():void
    {
        $path=__DIR__."/../../public/images/".$_POST['uuid'];
        $deleted=unlink($path);
        $query = "delete from images where uuID like '{$_POST['uuid']}'";
        $this->connection->query($query);
        header('location: /image', true, 302);
    }
}
