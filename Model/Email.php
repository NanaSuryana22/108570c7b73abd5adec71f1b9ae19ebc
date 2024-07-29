<?php
class EmailModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function insert_email($user_id, $recipient, $subject, $body, $status) {
        $stmt = $this->pdo->prepare('INSERT INTO emails (user_id, recipient, subject, body, status) VALUES (:user_id, :recipient, :subject, :body, :status)');
        return $stmt->execute([
            'user_id' => $user_id, 
            'recipient' => $recipient, 
            'subject' => $subject,
            'body' => $body,
            'status' => $status
        ]);
    }

    public function data_email($user_id) {
        $stmt = $this->pdo->prepare('SELECT * FROM emails WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetch();
    }
}
?>
