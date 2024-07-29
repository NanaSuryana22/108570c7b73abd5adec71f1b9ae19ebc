<?php
require './Config/koneksi.php';
require './Controller/UserController.php';
require './Controller/EmailController.php';

// Simple Router
$request = trim($_SERVER['REQUEST_URI'], '/');
$requestParts = explode('/', $request);
$action = $requestParts[1] ?? '';

$pdo = $GLOBALS['pdo'];
$userController = new UserController($pdo);
$emailController = new EmailController($pdo);

switch ($action) {
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->registerAction();
        } else {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
        }
        break;
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->loginAction();
        } else {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
        }
        break;
    case 'logout':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->logoutAction();
        } else {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
        }
        break;
    case 'send_email':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $emailController->send_email();
        } else {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
        }
        break;
    case 'data_email':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $emailController->data_email();
        } else {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
        }
        break;
    default:
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}
?>
