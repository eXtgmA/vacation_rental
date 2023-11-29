<?php

namespace src\models;

use Exception;
use mysqli_result;

class User extends BaseModel
{
    private int $id;
    private string $name;
    private string $password;
    private string $email;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Login the user
     *
     * @param string $username
     * @param string $password
     * @return void
     */
    public function login(string $username, string $password): void
    {
        try {
            $query = "select * from users where name = '{$username}' limit 1";
            $sql = $this->connection->query($query);
            if ($sql instanceof mysqli_result) {
                $result = $sql->fetch_object('src\models\User');
                // Check if there is a user with the send login mail or username
                if (isset($result) && $result instanceof User) {
                    if (password_verify($password, $result->password)) {
                        // if everything is ok perform login and set user as active user for the session
                        session_start();
                        $_SESSION['user'] = $result->id;
                        header("location : {$_SERVER['HTTP_ORIGIN']}/dashboard", true, 302);
                    } else {
                        error_log('"' . $result->name . '" tried to login with wrong password');
                        throw new Exception('login fehlgeschlagen');
                    }
                } else {
                    error_log('User "' . $username . '" does not exist');
                    throw new Exception('Benutzer "' . $username . '" existiert nicht');
                }
            }
        } catch (Exception $exception) {
            session_unset();
            session_start();
            $_SESSION['message'] = $exception->getMessage();
            header("location : {$_SERVER['HTTP_ORIGIN']}/login", true, 302);
        }
    }

    /**
     * Register a new user
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @return void
     */
    public function register(string $username, string $password, string $email): void
    {
        // Check if email is already taken
        try {
            //limiting to have less stress on database
            $query = "select * from users where email = '{$email}' limit 1";
            $sql = $this->connection->query($query);
            if ($sql instanceof mysqli_result) {
                $result = $sql->fetch_object();
                $result = $sql->num_rows;
                if ($result == 1) {
                    session_start();
                    $_SESSION['message'] = "Email bereits vergeben";
                    header('location : /register', true, 302);
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $query = "Insert INTO users (name,password,email) values ('{$username}','{$hashedPassword}','{$email}')";
                    $saved = $sql = $this->connection->query($query);
                    if (!$saved) {
                        $_SESSION['message'] = "Hoppla, da ist etwas schiefgelaufen";
                        header("location: /register", true, 302);
                    }
                    $userId = $this->connection->insert_id; // get id after creation
                    $_SESSION['user'] = $userId; // login aver successful creation
                    header("location: /dashboard", true, 302);
                }
            }
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
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
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
}
