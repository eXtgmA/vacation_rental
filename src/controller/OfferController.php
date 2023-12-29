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

    /**
     * @return void
     * @throws Exception
     *
     * Returning all the Houses a user owns and want to offer
     *
     */
    public function getIndex(): void
    {
        // get all houses
        $houses = $this->getAllHousesBelongingToTheCurrentUser();
        $param['houses'] = $houses ?? [];
        // prepare calendar input (get all booked days)
        foreach ($param['houses'] as $house) {
            $param['bookedDays'][$house->getId()] = $house->getBookedDates(); // @phpstan-ignore-line
        }
        new ViewController('offerIndex', $param);
    }

    /**
     * @return void
     *
     * deliver the creation page for a new house (offer)
     */
    public function getCreate(): void
    {
        // get all existing features
        $param['features'] = $this->prepareFeatures();
        new ViewController('createNewOffer', $param);
    }


    function sanitizeInput(&$item, $key) {
        $item = htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
    }

    /**
     * @return void
     *
     * Post process of creating a new house
     */
    public function postCreate(): void
    {
        array_walk_recursive($_POST, array($this,"sanitizeInput"));

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
                $newImages = $this->translateOptionalImagesInput();
                foreach ($newImages as $image) {
                    $uuidO = Image::imageToDisk($image);
                    $optionalImage = new Image(['house_id' => $house->getId(), 'typetable_id' => 4, 'uuid' => $uuidO]);
                    $optionalImage->save();
                }
            }

            // save all selected features
            if (isset($_POST['features'])) {
                foreach ($_POST['features'] as $categoryName => $category) {
                    foreach ($category as $featureName) {
                        $query = "INSERT INTO houses_has_features (houses_id, features_id) VALUES ( {$house->getId()}, (SELECT id FROM features WHERE name='{$featureName}' LIMIT 1) );";
                        $this->connection()->query($query);
                    }
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
     * @param int $houseId
     * @return void
     * @throws Exception
     *
     * toggling a house between active and inactive
     */
    public function posttoggleStatus(int $houseId = null): void
    {
        $house=$this->forceParam($houseId, 'House');
        $house->toggleStatus();
        redirect("/offer", 302);
    }

    /**
     * @param int|null $houseId
     * @return void
     * @throws Exception
     *
     * deliver the details page for a customer NOT THE LANDLORD
     * This page is shown in booking process
     */
    public function getDetail($houseId = null): void
    {
        $house=$this->forceParam($houseId, 'House');
        new ViewController("offerDetail", $house);
    }

    /**
     * @param int|null $houseId
     * @return void
     *
     * delete a specific house
     * this includes deleting all images from db and disk
     */
    public function postDelete($houseId = null): void
    {
        $house=$this->forceParam($houseId, 'House');
        $this->isUserAllowedHere($houseId, 'house', '/offer');
        try {
            /** @var House $house */
            // prevent deleting houses that have dependencies in db (bookings)
            if ($house->getBookedDates() != "") {
                throw new Exception("Dependencies in database prevent deletion of house ({$houseId})");
            }
            $house->deleteHouse();
        } catch (Exception $e) {
            // disable the house instead of deleting it
            $house->toggleStatus(1);
            $_SESSION['message'] = 'Die Ferienwohnung kann nicht gelöscht werden, da sie bereits mindestens einmal gebucht wurde. Sie wurde stattdessen deaktiviert.';
            redirect($_SESSION['previous'], 302);
            die();
        }
        $_SESSION['message'] = "Die Ferienwohnung wurde erfolgreich gelöscht";
        redirect($_SESSION['previous'], 302);
    }

    /**
     * @param int|null$houseId
     * @return void
     * @throws Exception
     *
     * Return edit page for a specific house
     * used by the landlord to manage his house
     */
    public function getEdit($houseId = null): void
    {
        $param['house']=$this->forceParam($houseId, 'House');
        $this->isUserAllowedHere($houseId, 'House', '/offer');
        // get all existing features
        $param['features'] = $this->prepareFeatures();
        // get names of all the features related to the house
        $param['featuresSelected'] = [];
        foreach ($param['house']->getAllFeatures() as $feature) {
            $param['featuresSelected'][] = $feature->getName();
        }

        new ViewController('offerEdit', $param);
    }

    /**
     * @param int $houseId
     * @return void
     * @throws Exception
     *
     * post process for updating a specific house
     */
    public function postEdit($houseId): void
    {
        $house=$this->forceParam($houseId, 'House');
        $this->isUserAllowedHere($houseId, 'house', '/offer');

        // update base data
        /** @var House $house */
        $baseData = $_POST['base-data'];
        $house->update($baseData);

        if (!$_POST['features']) {
            $_POST['features'] = [];
        };
        $this->updateFeatures($house, $_POST['features']);
        $this->updateTags($houseId, $_POST['tags']);

        // update images last, because redirect on failure is included
        $this->updateImages($house, $_FILES);

        redirect("/offer/edit/{$houseId}", 302);
    }

    /**
     * Add new images and delete removed ones
     *
     * It uses the following keys for data input
     * - 'front-image-input'
     * - 'layout-image-input'
     * - 'optional-images'
     *
     * @param House $house
     * @param array<array<string>> $postedFiles
     * @return void
     */
    private function updateImages(House $house, array $postedFiles) : void
    {
        // todo userallowed

        try {
            // update front image
            if ($postedFiles['front-image-input']['name'] != '') {
                /** @var Image $image */
                $image = $this->find('\src\models\Image', 'uuid', $house->getFrontImage(), 1);
                if ($image != null) {
                    $image->updateImage($postedFiles['front-image-input']);
                }
            }
            // update layout image
            if ($postedFiles['layout-image-input']['name'] != '') {
                /** @var Image $image */
                $image = $this->find('\src\models\Image', 'uuid', $house->getLayoutImage(), 1);
                if ($image != null) {
                    $image->updateImage($postedFiles['layout-image-input']);
                }
            }
            // update optional images
            $oldImages = $house->getOptionalImages();
            if ($postedFiles['optional-images']['name'][0] != '') {
                $newImages = $this->translateOptionalImagesInput();
                foreach ($newImages as $newImage) {
                    $found = false;
                    foreach ($oldImages as $key => $oldImage) {
                        if ($newImage['name'] == $oldImage->getUuid()) {
                            $found = true;
                            // keep track of found images
                            unset($oldImages[$key]);
                            break;
                        }
                    }
                    if (!$found) {
                        // save new image to db and disk
                        $uuid = Image::imageToDisk($newImage);
                        $optionalImage = new Image(['house_id' => $house->getId(), 'typetable_id' => 4, 'uuid' => $uuid]);
                        $optionalImage->save();
                    }
                }
            }
            // delete deselected optional images from db and disk
            if (count($oldImages) > 0) {
                foreach ($oldImages as $oldImage) {
                    $imgPath = $oldImage->deleteImage();
                    unlink($imgPath);
                }
            }
        } catch (Exception $e) {
            $_SESSION['message'] = "Manche Bilder konnten nicht übernommen werden";
            redirect('/offer/edit/'.$house->getId(), 302);
            die();
        }
    }

    /**
     * Insert newly selected features and delete deselected ones (relative to a given house)
     * It uses the array $_POST['features'] for data input
     *
     * @param House $house
     * @param array<array<string>> $postedFeatures
     * @return void
     */
    private function updateFeatures(House $house, array $postedFeatures) : void
    {
        // todo userallowed

        $houseFeatures = $house->getAllFeatures();
        foreach ($postedFeatures as $category) {
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
        // remove house-feature relation from db if user deselected it
        if (count($houseFeatures) > 0) {
            foreach ($houseFeatures as $houseFeature) {
                $query = "DELETE FROM houses_has_features WHERE houses_id={$house->getId()} AND features_id={$houseFeature->getId()};";
                $this->connection()->query($query);
            }
        }
    }

    /**
     * @param array<string, array<mixed>> $param
     * @return void
     *
     * deliver the search form to the user
     * If there are prefilled search parameters keep fields filled out
     */
    public function getFind($param)
    {
        // prepare search parameter and save them into session if they exist
            // (data source: 1. dashboard or 2. session or 3. default )
        /** @var string $destination */
        $destination = $_SESSION['search-data']['destination'] = $param['destination'] ?? $_SESSION['search-data']['destination'] ?? '';
        /** @var string $dateStart */
        $dateStart = $_SESSION['search-data']['dateStart'] = $param['dateStart'] ?? $_SESSION['search-data']['dateStart'] ?? '';
        /** @var string $dateEnd */
        $dateEnd = $_SESSION['search-data']['dateEnd'] = $param['dateEnd'] ?? $_SESSION['search-data']['dateEnd'] ?? '';
        /** @var string $persons */
        $persons = $_SESSION['search-data']['persons'] = (int)($param['persons'] ?? $_SESSION['search-data']['persons'] ?? 0);

        // prepare query
        $query = "
SELECT *
FROM houses h
WHERE h.id NOT IN (
    SELECT house_id
    FROM bookingpositions b
    WHERE b.id IN (
        SELECT id
        FROM bookingpositions b2
        WHERE b2.booking_id IN (
            SELECT id
            FROM bookings b
            WHERE b.is_confirmed = 1
        )
        AND (
            (b2.date_start BETWEEN '{$dateStart}' AND '{$dateEnd}')
            OR (b2.date_end BETWEEN '{$dateStart}' AND '{$dateEnd}')
            OR ('{$dateStart}' BETWEEN b2.date_start AND b2.date_end)
        )
    )
)
and
    city like '%{$destination}%'
and
    is_disabled = 0
";
        if ($persons > 0) {
            $query .= "and max_person >= {$persons}";
        }
        // execute query
        $result = $this->connection()->query($query);

        $houses = [];
        $houseCount = 0;
        if ($result instanceof \mysqli_result) {
            while ($row = $result->fetch_object('\src\models\House')) {
                $houses[] = $row;
                $houseCount++;
            }
        }

        // unset old data
        $param = [];

        // prepare displaying all features
        $param['features'] = $this->prepareFeatures();

        // prefill filter (features and tags) with old data
        $param['featuresSelected'] = $_GET['features'] ?? [];
        $param['tagsSelected'] = $_GET['tags'] ?? '';

        $param['houseCount'] = $houseCount;
        $param['houses'] = $houses;
        new ViewController('search', $param);
    }

    /**
     * Add newly provided tags and delete all missing ones
     *
     * @param int $houseId
     * @return void
     * @throws Exception
     */
    private function updateTags(int $houseId, string $postedTags): void
    {
        // todo userallowed

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
        if (strlen($tags) == 0) {
            // continue with empty array if empty tag string is provided
            $tags = [];
        } else {
            $tags = explode(',', $tags);
            $tags = array_unique($tags);
        }

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
     * Save all given tags for a house in database
     *
     * @param string $postedTags
     * @param int $houseId
     * @return void
     *
     *actual storing process in db
     */
    private function storeTags(string $postedTags, int $houseId) : void
    {
        // todo userallowed

        $tags = $postedTags;
        // only add tags if there is at least one tag provided
        if (strlen($tags) > 0) {
            $tags = explode(',', $tags);
            $tags = array_unique($tags);

            foreach ($tags as $tag) {
                $newTag = new Tag(['name' => $tag, 'house_id' => $houseId]);
                $newTag->save();
            }
        }
    }

    /**
     * Get all existing features sorted by category
     *
     * @return array<string, array<Feature>|false>
     */
    private function prepareFeatures() : array
    {
        $list['Outdoor'] =    Feature::getFeaturesByCategory('Outdoor');
        $list['Wellness'] =   Feature::getFeaturesByCategory('Wellness');
        $list['Bad'] =        Feature::getFeaturesByCategory('Bad');
        $list['Multimedia'] = Feature::getFeaturesByCategory('Multimedia');
        $list['Küche'] =      Feature::getFeaturesByCategory('Küche');
        $list['Sonstiges'] =  Feature::getFeaturesByCategory('Sonstiges');
        return $list;
    }

    /**
     * Translates input of $_FILE[] into an array of files
     *
     * @return array<array<string>>
     */
    private function translateOptionalImagesInput() : array
    {
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
        }
        return $oFiles;
    }

    /**
     * storing filters to session
     * @return void
     */
    public function postStoreFilter():void
    {
        // because we aren't using the regular form we have to manually pick end encode our data
        $rawData = file_get_contents("php://input");
        if ($rawData != false) {
            /** @var array<array<string>> $postData */
            $postData = json_decode($rawData, true);

            $features = $postData['features'];
            $tags = $postData['tags'];

            $_SESSION['filter'] = ['tags'=>$tags, 'features'=>$features];
            echo json_encode(['message' => 'Daten erfolgreich erhalten und verarbeitet.']);
            die();
        }
        echo json_encode(['message' => 'Etwas lief beim speichern schief']);
    }

    /**
     * returning the stored session filter to js
     * @return void
     */
    public function getFilter()
    {
        // because we aren't using the regular form we have to manually pick end encode our data
        $filter = $_SESSION['filter'];
        echo json_encode(['filter'=>$filter,'message' => 'Daten erfolgreich erhalten und verarbeitet.']);
    }
}
