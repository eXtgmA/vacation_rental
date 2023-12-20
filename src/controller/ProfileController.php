<?php

namespace src\controller;

use src\models\User;

class ProfileController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getEdit()
    {
        $id = $_SESSION['user'];
        $user = $this->find('\src\models\User', 'id', $id, 1);
        return new ViewController('profile', $user);
    }

    public function postUpdate()
    {

        /** @var User $user */
        $user = $this->find('\src\models\User', 'id', $_SESSION['user'], 1);
        $user->enteredValidEmail($_POST['email'],"/profile");
        $user->sendUniqueEmail($_POST['email']);

        if ($_POST['password']) {
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }else{
            $_POST['password'] =$user->getPassword();
        }
        $user->update($_POST);


        $_SESSION['message'] = 'Daten wurden erfolgreich gespeichert';
        redirect("/profile", 302);
    }





}
