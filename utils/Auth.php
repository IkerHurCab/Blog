<?php

namespace utils;
class Auth {

    public function login($userData) {
        session_start();
        $_SESSION['user'] = $userData['username'];
        $_SESSION['email'] = $userData['email'];
        $_SESSION['role'] = $userData['role'];
    }
    public function check() {
        if (isset($_SESSION['user'])) {
            return true;
        }
        return false;
    }
    public function user() {
        return $_SESSION['user'];
    }
    public function logout() {
        session_destroy();
        header('Location: ../auth/login.php');
    }
}