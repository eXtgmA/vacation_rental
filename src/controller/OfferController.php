<?php

namespace src\controller;

use Exception;
use src\models\Features;
use src\models\House;
use src\models\Image;

class OfferController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getIndex(mixed $formdata = null): void
    {
        $houses = $this->getAllHousesBelongingToTheCurrentUser();
        // Return the House Data to our View
        new ViewController('offerIndex', $houses);
    }

    public function getCreate(): void
    {
        // get all existing features
        $param['features'] = $this->prepareFeatures();

        // todo : get all tags

        new ViewController('createNewOffer', $param);
    }

    public function postCreate(): void
    {
//        add owner to attributes
        $houseInput = $_POST['base-data'];
        $houseInput['owner_id'] = $_SESSION['user'];
//        create house with values
        $house = new House($houseInput);
        $house->save();

        try {
            // save front image
            $uuidF = Image::imageToDisk($_FILES['front-image-input']);
            $frontimage = new Image(['house_id' => $house->getId(), 'typetable_id' => 1, 'uuid' => $uuidF]);
            $frontimage->save();

            // save layout image
            $uuidL = Image::imageToDisk($_FILES['layout-image-input']);
            $layoutimage = new Image(['house_id' => $house->getId(), 'typetable_id' => 2, 'uuid' => $uuidL]);
            $layoutimage->save();

            // save all additional images (if exist)
            if (isset($_FILES['optional-images'])) {
                $fCount = count($_FILES['optional-images']['name']);
                $oFiles = [];
                for ($i = 0; $i < $fCount; $i++) {
                    // translate input array format
                    $oFiles[$i]['name'] = $_FILES['optional-images']['name'][$i];
                    $oFiles[$i]['full_path'] = $_FILES['optional-images']['full_path'][$i];
                    $oFiles[$i]['type'] = $_FILES['optional-images']['type'][$i];
                    $oFiles[$i]['tmp_name'] = $_FILES['optional-images']['tmp_name'][$i];
                    $oFiles[$i]['error'] = $_FILES['optional-images']['error'][$i];
                    $oFiles[$i]['size'] = $_FILES['optional-images']['size'][$i];

                    // save one additional image
                    $uuidO = Image::imageToDisk($oFiles[$i]);
                    $optionalimage = new Image(['house_id' => $house->getId(), 'typetable_id' => 4, 'uuid' => $uuidO]);
                    $optionalimage->save();
                }
            }
        } catch (Exception $e) {
            try {
                // delete house and all its images
                $house->deleteHouse();
            } catch (Exception $e) {
                error_log("House and its images could not be deleted after creation process has failed");
            }

            // redirect
            $_SESSION['message'] = "Hoppla, da ist wohl etwas schief gelaufen. Das Haus konnte nicht angelegt werden.";
            redirect($_SESSION['previous'], 500, $_POST);
        }

        $_SESSION['message'] = "Das Haus wurde erfolgreich angelegt und kann ab jetzt gemietet werden";
        redirect('/offer', 302);
    }

    /**
     * @return mixed[]|null
     * @throws Exception
     */
    public function getAllHousesBelongingToTheCurrentUser(): ?array
    {
        $houses = $this->find('src\models\House', 'owner_id', $_SESSION['user']);
        return $houses;
    }

    /**
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function posttoggleStatus(int $id): void
    {
        $house = $this->find('\src\models\House', 'id', $id, 1);
        $house->toggleStatus();
        header('location: /offer', true, 302);
    }

    /**
     * @param $id
     * @return void
     * @throws Exception
     */
    public function getshow(int $id): void
    {
        $house = $this->find('\src\models\House', 'id', $id, 1);
        new ViewController("offerDetail", $house);
    }

    public function postDelete(int $houseId): void
    {
        try {
            /** @var House $house */
            $house = $this->find('\src\models\House', 'id', $houseId, 1);
            $house->deleteHouse();
        } catch (Exception $e) {
            $_SESSION['message'] = 'Haus konnte nicht gelöscht werden. Gibt es Buchungen ? (->bookingpositions)';
            redirect($_SESSION['previous'], 500);
        }
        redirect($_SESSION['previous'], 302);
    }

    public function getEdit(int $houseId = null): void
    {
        if (!$houseId) {
            // fallback when missing param in url
            redirect('/dashboard', 302);
        }

        // get all existing features
        $param['features'] = $this->prepareFeatures();

        $param['house'] = $this->find('\src\models\House', 'id', $houseId, 1);
        new ViewController('offerEdit', $param);
    }

    public function postEdit(int $houseId): void
    {
        // update base data
        /** @var House $house */
        $house = $this->find('\src\models\House', 'id', $houseId, 1);
        $baseData = $_POST['base-data'];
        $house->update($baseData);

        // update images
        try {
            // update front image
            if ($_FILES['front-image-input']['name'] != '') {
                /** @var Image $image */
                $image = $this->find('\src\models\Image', 'uuid', $house->getFrontImage(), 1);
                if ($image != null) {
                    $image->updateImage($_FILES['front-image-input']);
                }
            }
            // update layout image
            if ($_FILES['layout-image-input']['name'] != '') {
                /** @var Image $image */
                $image = $this->find('\src\models\Image', 'uuid', $house->getLayoutImage(), 1);
                if ($image != null) {
                    $image->updateImage($_FILES['layout-image-input']);
                }
            }
            // update optional images
            if ($_FILES['optional-images']['name'][0] != '') {
                // todo : update the correct optional image (needs order for images)
            }
        } catch (Exception $e) {
            $_SESSION['message'] = "Manche Fotos konnten nicht ausgetauscht werden";
            redirect('/offer/edit/'.$houseId, 302);
        }

        // todo update features
        // todo update tags
        redirect("/offer/edit/{$houseId}", 302);
    }

    /**
     * @param array<string>$param
     * @return void
     */
    public function getFind($param):void
    {
        //prepare search parameter
        $destination = $param['destination'];
        $dateStart = $param['dateStart'];
        $dateEnd = $param['dateEnd'];
        $persons = $param['persons'];

        $query = "select * from houses where city like '%{$destination}%'";
        $result=$this->connection()->query($query);
        $houses = [];
        if ($result instanceof \mysqli_result) {
            while ($row=$result->fetch_object('\src\models\House')) {
                $houses[] = $row;
            }
        }
        $param['houses'] = $houses;
        new ViewController('search', $param);
    }

    /**
     * Get all existing features sorted by category
     *
     * @return array<string, array<Features>|false>
     */
    public function prepareFeatures() : array
    {
        $list['Outdoor'] =    Features::getFeaturesByCategory('Outdoor');
        $list['Wellness'] =   Features::getFeaturesByCategory('Wellness');
        $list['Bad'] =        Features::getFeaturesByCategory('Bad');
        $list['Multimedia'] = Features::getFeaturesByCategory('Multimedia');
        $list['Küche'] =      Features::getFeaturesByCategory('Küche');
        $list['Sonstiges'] =  Features::getFeaturesByCategory('Sonstiges');
        return $list;
    }
}
