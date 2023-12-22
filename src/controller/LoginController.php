<?php
namespace src\controller;

use src\models\User;

class LoginController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return the login Mask
     *
     * @param mixed $formdata
     * @return void
     */
    public function getLogin(mixed $formdata = null): void
    {
        $this->redirectIfLoggedIn();
        new ViewController('loginIndex');
    }

    public function postLogin(): void
    {
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
        $user = $this->find('\src\models\User', 'email', $email, 1);
        if ($user) {
            $user->login($email, $password);

            // Redirect to previous page
            if (isset($_SESSION['redirect_back'])) {
                $redirect_url = $_SESSION['redirect_back'];
                unset($_SESSION['redirect_back']);
                redirect($redirect_url, 302);
            } else {
                // Redirect to landing page
                redirect("/dashboard", 302);
            }
            die();
        } else {
            $_SESSION['message'] = 'Ungültige E-Mail-Adresse';
            $previous = $_SESSION['previous'];
            redirect($previous, 302);
            die();
        }
    }
}
