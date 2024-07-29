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
            $full_name = $data['first_name'] . ' ' . $data['last_name'];
            $mail_to = isset($_POST['to']) ? $_POST['to'] : '';
            $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
            $body = isset($_POST['body']) ? $_POST['body'] : '';

            try {
                $mail = new PHPMailer(true);
                //Server settings
                $mail->SMTPDebug = 2;                                       
                $mail->isSMTP();                                        
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'nanasuryana554@gmail.com';
                $mail->Password   = 'etjj dcwr epbn looe';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
            
                // Penerima
                $mail->setFrom($data['email'], $full_name);
                $mail->addAddress($mail_to);
                $mail->addReplyTo('nanasuryana554@gmail.com', 'Email From Nana Suryana');
            
                // Isi Email
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $body;
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            
                $mail->send();
                $this->emailModel->insert_email($data['id'],$mail_to,$subject,$body,'sent');
                echo json_encode(['status' => 'Success']);
            } catch (Exception $e) {
                $this->emailModel->insert_email($data['id'],$mail_to,$subject,$body,'failed');
                echo json_encode(['status' => 'Failed to send email', 'Reason' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
            }
        } else {
            echo json_encode(['status' => 'Failed Send Email.', 'Reason' => 'You Must Login First.']);
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