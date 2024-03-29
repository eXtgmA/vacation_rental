<?php
namespace src\controller;

use src\models\User;

class RegisterController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getform(mixed $formdata = null): void
    {
        $this->redirectIfLoggedIn();
        new ViewController('register');
    }
    public function postform(): void
    {
        $password = $_POST['password'];
        $this->sanitize($_POST);
        $input = $_POST;
        $input['password'] = $password;
        $user=new User($input);
        $_SESSION['old_POST'] = $_POST;

        if ($_POST['forename']==null) {
            $_SESSION['message'] = "Vorname ist ein Pflichtfeld";
            redirect("/register", 302);
            die();
        }
        if ($password==null) {
            $_SESSION['message'] = "Passwort ist ein Pflichtfeld";
            redirect("/register", 302);
            die();
        }
        if ($_POST['surname']==null) {
            $_SESSION['message'] = "Nachname ist ein Pflichtfeld";
            redirect("/register", 302);
            die();
        }


        $userExists=$user->checkIfExist();
        if ($userExists) {
            $_SESSION['message'] = 'E-Mail-Adresse bereits vergeben';
            redirect("/register", 302);
            die();
        }
        // if email validation fails => redirect and notification are provided
        $user->enteredValidEmail($_POST['email'], "/register");

        $user->register();

        redirect("/login", 302);
    }
}
