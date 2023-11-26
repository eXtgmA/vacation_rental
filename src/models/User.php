<?php

namespace src\models;



class User extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login($username,$password)
    {
        try {
        $query = "select * from users where name = '{$username}' limit 1";
        $sql=$this->connection->query($query);
        $result=$sql->fetch_object();
        if($result->password == $password){
            session_start();
            $_SESSION['user'] = $result->id;
        }
        else{
            throw new \Exception('login fehlgeschlagen');
        }
        }catch (\Exception $exception){
            session_unset();
            var_dump($_SESSION['message']);
            session_start();
            $_SESSION['message'] = $exception->getMessage();
            header("location : {$_SERVER['HTTP_ORIGIN']}/login",true,302);
        }
    }
}