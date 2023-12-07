<?php

namespace src\models;

use src\helper\DatabaseTrait;

include_once("../src/config/database.php");
include_once("../src/helper/redirect.php");

class BaseModel
{
    use DatabaseTrait;
    protected function __construct()
    {
    }
}
