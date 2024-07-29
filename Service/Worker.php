<?php
require '../vendor/autoload.php';
require_once __DIR__ . '../../Model/Email.php';
require_once __DIR__ . '../../Model/User.php';
require_once __DIR__ . '/Queue.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Worker {
    private $queueFile;
    private $emailModel;
    private $pdo;

    public function __construct($queueFile, $pdo) {
        $this->queueFile = $queueFile;
        $this->pdo = $pdo;
        $this->emailModel = new EmailModel($pdo);
    }

    public function processQueue() {
        $queue = new Queue($this->queueFile);
        $tasks = $queue->getTasks();
        foreach ($tasks as $task) {
            $this->sendEmail($task);
        }
        $queue->clearTasks();
    }

    private function sendEmail($task) {
        $user_id = $task['user_id'];
        $to = $task['to'];
        $subject = $task['subject'];
        $body = $task['body'];

        // Fetch user information from the database
        $user = (new UserModel($this->pdo))->getUserById($user_id);
        if (!$user) {
            echo "User not found\n";
            return;
        }

        $full_name = $user['first_name'] . ' ' . $user['last_name'];
        $email = $user['email'];

        try {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'nanasuryana554@gmail.com';
            $mail->Password   = 'etjj dcwr epbn looe';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom($email, $full_name);
            $mail->addAddress($to);
            $mail->addReplyTo('nanasuryana554@gmail.com', 'Email From Nana Suryana');

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            $mail->send();
            $this->emailModel->insert_email($user_id, $to, $subject, $body, 'sent');
            echo "Email sent successfully\n";
        } catch (Exception $e) {
            $this->emailModel->insert_email($user_id, $to, $subject, $body, 'failed');
            echo "Failed to send email: {$mail->ErrorInfo}\n";
        }
    }
}

$pdo = new PDO('pgsql:host=localhost;dbname=TugasEmailNanaSuryana', 'postgres', 'nanasuryana');
$worker = new Worker('email_queue.json', $pdo);
$worker->processQueue();
?>
