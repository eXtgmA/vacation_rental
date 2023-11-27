<?php
namespace src\models;

use MongoDB\Driver\Query;

class House extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addhouse($param)
    {
        // prepare statement
        $query = "insert into houses ( owner,";
        $i = 1;
        $paramLength=var_dump(count($param));

        foreach ($param as $key=>$value){
            if($i < $paramLength){
                $query = $query .$key.",";
            }else{
                $query = $query .$key;
            }
            $i++;
        }
        $query2 = "insert into houses ( owner,sqm,maxpersons,rooms,pricepernight,zipcode,streetname,streetnumber) Values ( '1','1','1','1','1','1','1','1')";
        $query = $query . ") Values ( '{$_SESSION['user']}',";
        $i = 1;
        foreach ($param as $key=>$value){
            if($i < $paramLength){
                $query = $query ."'". $value."',";
            }else{
                $query = $query ."'". $value."'";
            }
            $i++;
        }
        $query = $query . ")";
        try {

            $result = $this->connection->query($query);
        } catch (\Exception $e) {
            var_dump($e);
        }
        var_dump($result);
    }
}