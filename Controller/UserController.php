<?php

require_once __DIR__ . '../../Model/User.php';
require './vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new UserModel($pdo);
    }

    public function register($email, $password, $first_name, $last_name) {
        return $this->userModel->register($email, $password, $first_name, $last_name);
    }

    public function login($email, $password) {
        $user = $this->userModel->login($email);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            return $user;
        }
        return false;
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
    }

    public function registerAction() {
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';

        if ($this->userModel->emailExists($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Email already registered']);
        } else {
            if ($this->register($email, $password, $first_name, $last_name)) {
                echo json_encode(['status' => 'success', 'message' => 'Registration successful']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Registration failed']);
            }
        }
    }

    public function loginAction() {
        $header = apache_request_headers();
        if (isset($header['Authorization'])) {
            $header = $header['Authorization'];
            $sec_key = '85ldofi';
            $decode = JWT::decode($header, new Key($sec_key, 'HS256'));

            echo json_encode(['status' => 'Already login.', 'user_information' => $decode]);
        } else {
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            if ($user_info = $this->login($email, $password)) {
                $sec_key = '85ldofi';
                $encode = JWT::encode($user_info, $sec_key, 'HS256');
                
                echo json_encode(['status' => 'success', 'message' => 'Login successful', 'user_info' => $user_info, 'JWT' => $encode]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Login failed']);
            }
        }
    }

    public function logoutAction() {
        $this->logout();
        echo json_encode(['status' => 'success', 'message' => 'Logout successful']);
    }
}
?>
