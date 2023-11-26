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
        // Check if there is a user with the send login mail or username
        if($result->password == $password){
            // if everything is ok perform login and set user as active user for the session
            session_start();
            $_SESSION['user'] = $result->id;
            header("location : {$_SERVER['HTTP_ORIGIN']}/dashboard",true,302);
        }
        else{
            throw new \Exception('login fehlgeschlagen');
        }
        }catch (\Exception $exception){
            session_unset();
            session_start();
            $_SESSION['message'] = $exception->getMessage();
            header("location : {$_SERVER['HTTP_ORIGIN']}/login",true,302);
        }
    }
}