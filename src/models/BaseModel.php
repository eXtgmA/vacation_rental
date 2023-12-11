<?php

namespace src\models;

use src\helper\DatabaseTrait;
use src\helper\ValidationTrait;

include_once("../src/config/database.php");
include_once("../src/helper/redirect.php");

class BaseModel
{
    use DatabaseTrait,ValidationTrait;

    /**
     * @param string[] $modelData
     */
    protected function __construct($modelData=null)
    {
        if ($modelData) {
            // Create a new Object from given input
            $this->createFromModelData($modelData);
        }
    }

    /**
     * @param array<int|string> $modelData
     */
    protected function createFromModelData($modelData):void
    {
        $class = get_class($this);
        $allowedAttributes = $class::$allowedAttributes;
        // go through each given parameter and check if key exist
        foreach ($modelData as $key => $value) {
            if (in_array($key, $allowedAttributes)) {
//                transform square_meter_param to SquareMeterParam
                $method=str_replace(' ', '',ucwords(str_replace('_', ' ', $key)));
                $method = "set" . $method;
                $this->$method($value);
                $index=(array_search($key, $allowedAttributes));
                unset($allowedAttributes[$index]);
            }
        }
        foreach ($allowedAttributes as $key) {
            // every property which is not given will be set to 0 as fallback
            // we HAVE to initialize these Values, or we avoid calling them
            $method=str_replace(' ', '',ucwords(str_replace('_', ' ', $key)));
            $method = "set" . $method;
            $this->$method(0);
        }
    }
}
