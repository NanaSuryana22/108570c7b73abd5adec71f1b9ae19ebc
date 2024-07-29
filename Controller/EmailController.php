<?php

require './vendor/autoload.php';
require_once __DIR__ . '../../Model/Email.php';
require_once __DIR__ . '../../Service/Queue.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class EmailController {
    private $emailModel;
    private $queue;

    public function __construct($pdo) {
        $this->emailModel = new EmailModel($pdo);
        $this->queue = new Queue('email_queue.json');
    }

    public function send_email() {
        $header = apache_request_headers();
        if (isset($header['Authorization'])) {
            $header = $header['Authorization'];
            $sec_key = '85ldofi';
            $decode = JWT::decode($header, new Key($sec_key, 'HS256'));
            $data = (array) $decode;

            $mail_to = isset($_POST['to']) ? $_POST['to'] : '';
            $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
            $body = isset($_POST['body']) ? $_POST['body'] : '';

            $this->queue->addTask([
                'user_id' => $data['id'],
                'to' => $mail_to,
                'subject' => $subject,
                'body' => $body
            ]);

            echo json_encode(['status' => 'Success, email task added to the queue']);
        } else {
            echo json_encode(['status' => 'Failed to send email', 'Reason' => 'You must login first.']);
        }
    }

    public function data_email() {
        $header = apache_request_headers();
        if (isset($header['Authorization'])) {
            $header = $header['Authorization'];
            $sec_key = '85ldofi';
            $decode = JWT::decode($header, new Key($sec_key, 'HS256'));
            $data = (array) $decode;
            $data_email = $this->emailModel->data_email($data['id']);
            echo json_encode(['status' => 'Success.', 'Data Email' => $data_email]);
        } else {
            echo json_encode(['status' => 'Failed.', 'Reason' => 'You Must Login First.']);
        }
    }
}

?>