<?php

namespace src\models;

use src\helper\DatabaseTrait;

include_once("../src/config/database.php");

class BaseModel
{
use DatabaseTrait;
    protected function __construct()
    {
    }
}
