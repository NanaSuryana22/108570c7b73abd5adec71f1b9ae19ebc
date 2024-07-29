<?php


class UserModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($email, $password, $first_name, $last_name) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare('INSERT INTO users (email, password, first_name, last_name) VALUES (:email, :password, :first_name, :last_name)');
        return $stmt->execute([
            'email' => $email, 
            'password' => $hashed_password, 
            'first_name' => $first_name, 
            'last_name' => $last_name
        ]);
    }

    public function login($email) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function emailExists($email) {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function getUserById($user_id) {
        $stmt = $this->pdo->prepare('SELECT id, email, first_name, last_name FROM users WHERE id = :id');
        $stmt->execute(['id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
