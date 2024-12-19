<?php
namespace controllers;
use models\User;
use utils\Auth;
require_once __DIR__ . '/../utils/Auth.php';
class UserController
{
    private $userModel;
    public function __construct(User $user)
    {
        $this->userModel = $user;
    }
    public function login(User $user) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $userData = $user->findByEmail($email);
        if ($userData && password_verify($password, $userData['password'])) {
            $login = new Auth();
            $login->login($userData);

            return true;
        } else {
            return false;
        }
    }
    public function logout () {
        $logout = new Auth();
        $logout->logout();
    }

    public function register($username, $email, $password, $role) {
        return $this->userModel->store($username, $email, $password, $role);
    }

    
}