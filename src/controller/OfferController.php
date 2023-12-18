<?php

namespace src\controller;

use Exception;
use src\models\Feature;
use src\models\House;
use src\models\Image;
use src\models\Tag;

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

        $tags = $_POST['tags'];
        $this->storeTags($tags, $house->getId());

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
            if ($_FILES['optional-images']['name'][0] != '') {
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

            // save all selected features
            foreach ($_POST['features'] as $categoryName => $category) {
                foreach ($category as $featureName) {
                    $query = "INSERT INTO houses_has_features (houses_id, features_id) VALUES ( {$house->getId()}, (SELECT id FROM features WHERE name='{$featureName}' LIMIT 1) );";
                    $this->connection()->query($query);
                }
            }
        } catch (Exception $e) {
            // delete all related features
            $house->resetRelatedFeatures();

            try {
                // delete house and all its images
                $house->deleteHouse();
            } catch (Exception $e) {
                error_log("House and its images could not be deleted after creation process has failed");
            }

            // redirect
            $_SESSION['message'] = "Hoppla, da ist wohl etwas schief gelaufen. Das Haus konnte nicht angelegt werden.";
            redirect($_SESSION['previous'], 302, $_POST);
            die();
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

        // get house
        $param['house'] = $this->find('\src\models\House', 'id', $houseId, 1);

        // get all existing features
        $param['features'] = $this->prepareFeatures();
        // get names of all the features related to the house
        $param['featuresSelected'] = [];
        foreach ($param['house']->getAllFeatures() as $feature) {
            $param['featuresSelected'][] = $feature->getName();
        }

            new ViewController('offerEdit', $param);
    }

    public function postEdit(int $houseId): void
    {
        $this->updateTags($houseId, $_POST['tags']);

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

        // update features
        $this->updateFeatures($house);

        // todo update tags
        redirect("/offer/edit/{$houseId}", 302);
    }

    /**
     * Insert newly selected features and delete deselected ones (relative to a given house)
     *
     * It uses the array $_POST['features'] for data input
     *
     * @param House $house
     * @return void
     */
    public function updateFeatures(House $house) : void
    {
        $houseFeatures = $house->getAllFeatures();
        if (isset($_POST['features'])) {
            foreach ($_POST['features'] as $category) {
                foreach ($category as $featureName) {
                    $found = false;
                    foreach ($houseFeatures as $key => $houseFeature) {
                        // if exists in db => stop search for this name
                        if ($houseFeature->getName() == $featureName) {
                            $found = true;
                            // keep track of found features
                            unset($houseFeatures[$key]);
                            break;
                        }
                    }
                    // add house-feature relation to db if user just selected the feature
                    if (!$found) {
                        // if feature has not been found in existing house-feature relations list => it will be added
                        $query = "INSERT INTO houses_has_features (houses_id, features_id) VALUES ( {$house->getId()}, (SELECT id FROM features WHERE name='{$featureName}' LIMIT 1) );";
                        $this->connection()->query($query);
                    }
                }
            }
        }
        // remove house-feature relation from db if user deselected it
        if (count($houseFeatures) > 0) {
            foreach ($houseFeatures as $houseFeature) {
                $query = "DELETE FROM houses_has_features WHERE houses_id={$house->getId()} AND features_id={$houseFeature->getId()};";
                $this->connection()->query($query);
            }
        }
    }

    /**
     *
     * @param array<string, array<string>> $param
     * @return void
     */
    public function getFind($param)
    {
        //prepare search parameter
        /** @var string $destination */
        $destination = $param['destination'];
        $dateStart = $param['dateStart'];
        $dateEnd = $param['dateEnd'];
        $persons = $param['persons'];

        // get all existing features
        $param['features'] = $this->prepareFeatures();

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
     * @param int $houseId
     * @return void
     * @throws Exception
     */
    public function updateTags(int $houseId, string $postedTags): void
    {
//      getting old tags
        $oldTags = $this->find('\src\models\Tag', 'house_id', $houseId);
//       extract only the name
        $tempTags = [];
        foreach ($oldTags as $tag) {
            $tempTags[] = $tag->getName();
        }
//        --------------------------------------------------
//        preparing new tags
        $tags = $postedTags;
        $tags = explode(',', $tags);
        $tags = array_unique($tags);

//        --------------------------------------------------
//       In old array but not in new (has to be removed)
        $tagsToRemove = array_diff($tempTags, $tags);
        $tagsToRemove = "'" . implode("','", $tagsToRemove) . "'";
        $query = "delete from tags where house_id = {$houseId} and name in ({$tagsToRemove})";
        $this->connection()->query($query);

//       in new array but not in old (has to be removec)
        $newTagsToStore = array_diff($tags, $tempTags);
        foreach ($newTagsToStore as $tag) {
            $newTag = new Tag(['name' => $tag, 'house_id' => $houseId]);
            $newTag->save();
        }
    }

    /**
     * @param string $postedTags
     * @param int $houseId
     * @return void
     */
    public function storeTags(string $postedTags, int $houseId)
    {
        $tags = $postedTags;
        $tags = explode(',', $tags);
        $tags = array_unique($tags);

        foreach ($tags as $tag) {
            $newTag = new Tag(['name' => $tag, 'house_id' => $houseId]);
            $newTag->save();
        }
    }

    /**
     * Get all existing features sorted by category
     *
     * @return array<string, array<Feature>|false>
     */
    public function prepareFeatures() : array
    {
        $list['Outdoor'] =    Feature::getFeaturesByCategory('Outdoor');
        $list['Wellness'] =   Feature::getFeaturesByCategory('Wellness');
        $list['Bad'] =        Feature::getFeaturesByCategory('Bad');
        $list['Multimedia'] = Feature::getFeaturesByCategory('Multimedia');
        $list['Küche'] =      Feature::getFeaturesByCategory('Küche');
        $list['Sonstiges'] =  Feature::getFeaturesByCategory('Sonstiges');
        return $list;
    }
}
