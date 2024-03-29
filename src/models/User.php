<?php

namespace src\models;

use Exception;
use PHPStan\Type\ThisType;

class User extends BaseModel
{
    /**
     * @var int
     */
    private int $id;
    /**
     * @var string
     */
    private string $password;
    /**
     * @var string
     */
    private string $email;
    /**
     * @var string
     */
    private string $forename;
    /**
     * @var string
     */
    private string $surname;

    /**
     * @var string[]
     */
    public static array $allowedAttributes = ['surname', 'forename', 'password','email'];
    /**
     * @var string[]
     */
    public static array $requiredAttributes = ['surname', 'forename', 'password','email'];
    /**
     * @var string[]
     */
    public static array $updateableAttributes = ['surname', 'forename', 'password','email'];


    public static string $table = 'users';

    /**
     * @param string[] $modelData
     */
    public function __construct($modelData = null)
    {
        if ($modelData) {
            parent::createFromModelData($modelData);
        }
    }

    /**
     * Login the user
     *
     * @param string $email
     * @param string $password
     * @return void
     */
    public function login(string $email, string $password): void
    {
        try {
            $user = $this->find('src\models\User', 'email', $email, 1);

            // Check if there is a user with the send login mail or username
            if (password_verify($password, $user->getPassword())) {
                // if everything is ok perform login and set user as active user for the session
                // session_start();
                $_SESSION['user'] = $user->getId();
            } else {
                error_log('"' . $user->getEmail() . '" tried to login with wrong password');
                throw new Exception('login fehlgeschlagen');
            }
        } catch (Exception $exception) {
            // save redirect path
            $redirectBack = $_SESSION['redirect_back'] ?? '';
            // restart session
            session_unset();
            if (!isset($_SESSION)) {
                session_start();
            }
            // paste saved redirect path into new session and error provide message for failed login
            $_SESSION['redirect_back'] = $redirectBack;
            $_SESSION['message'] = "Login ist fehlgeschlagen";
            redirect('/login', 302, $_POST);
            die();
        }
    }

    /**
     * Register a new user
     *
     * @return void
     */
//    public function register(string $forename, string $surname, string $password, string $email): void
    public function register(): void
    {
        // Check if email is already taken
            $this->password=password_hash($this->password, PASSWORD_DEFAULT);
            $this->save();
            // after registration the user has to log in first
            redirect('/login', 302, $_POST);
    }

    /**
     * @param $email
     * @return void
     * @throws Exception
     */
    public function sendUniqueEmail(string $email) : void
    {
        if ($email == null) {
            // empty mail field, do nothing
            return;
        }
        if ($this->email == $email) {
            // email will be the same -> ok
            return;
        }

        $foundMail = $this->find('\src\models\user', 'email', $email, 1);
        if ($foundMail!=null) {
            unset($_SESSION['message']);
            $_SESSION['message'] = 'Mailadresse schon vergeben';
            redirect("/profile", 302);
            die();
        }
    }

    /**
     * @param string $email
     * @param string $redirect
     * @return void
     */
    public function enteredValidEmail(string $email, string $redirect) : void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }
        $_SESSION['message'] = 'E-Mail-Adresse ist ungültig';
        redirect($redirect, 302, $_POST);
        die();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getForename(): string
    {
        return $this->forename;
    }

    public function setForename(string $forename): void
    {
        $this->forename = $forename;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function checkIfExist():bool
    {
        $existingUser=$this->find('\src\models\User', 'email', $this->email, 1);
        if ($existingUser) {
            return true;
        }
        return false;
    }
}
