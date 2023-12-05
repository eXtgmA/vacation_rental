<?php

namespace src\models;

use Exception;
use src\helper\DatabaseTrait;

class User extends BaseModel
{
    use DatabaseTrait;
    private int $id;
    private string $password;
    private string $email;
    private string $forename;
    private string $surname;


    public function __construct()
    {
        parent::__construct();
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
            $query = "select * from users where email = '{$email}' limit 1";
            $sql = $this->fetch($query);
                $result = $sql->fetch_object('src\models\User');
                // Check if there is a user with the send login mail or username
                if ($result instanceof User) {
                    if (password_verify($password, $result->password)) {
                        // if everything is ok perform login and set user as active user for the session
                       // session_start();
                        $_SESSION['user'] = $result->id;
                        header("location: {$_SERVER['HTTP_ORIGIN']}/dashboard", true, 302);
                    } else {
                        error_log('"' . $result->email . '" tried to login with wrong password');
                        throw new Exception('login fehlgeschlagen');
                    }
                } else {
                    error_log('Konto für "' . $email . '" does not exist');
                    throw new Exception('Konto "' . $email . '" existiert nicht');
                }
        } catch (Exception $exception) {
            session_unset();
            session_start();
            $_SESSION['message'] = $exception->getMessage();
            header("location: {$_SERVER['HTTP_ORIGIN']}/login", true, 302);
        }
    }

    /**
     * Register a new user
     *
     * @param string $forename
     * @param string $surname
     * @param string $password
     * @param string $email
     * @return void
     */
    public function register(string $forename, string $surname, string $password, string $email): void
    {
        // Check if email is already taken
        try {
            //limiting to have less stress on database
            $query = "select * from users where email = '{$email}' limit 1";
            $sql = $this->fetch($query);
            $result = $sql->num_rows;
                // mail exist in db -> abort
                if ($result == 1) {
                    $_SESSION['message'] = "Email bereits vergeben";
                    header('location: /register', true, 302);
                }

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $query = "Insert INTO users (forename,surname,password,email) values ('{$forename}','{$surname}','{$hashedPassword}','{$email}')";
            /** @var User $user */
            $user = $this->storeAndReturn($query, '\\src\\models\\User','users');
                if ($user==null) {
                        $_SESSION['message'] = "Hoppla, da ist etwas schiefgelaufen";
                        header("location: /register", true, 302);
                }
                $_SESSION['user'] = $user->getId(); // login aver successful creation
                header("location: /dashboard", true, 302);

        } catch (Exception $e) {
            var_dump($e);
        }
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
}
