<?php
header('Content-Type: application/json');
// Require the model via a correct relative path from this controller file
require_once __DIR__ . '/../Models/sign-inModel.php';

$input = json_decode(file_get_contents("php://input"), true);
$email = trim($input['email'] ?? '');
$password = trim($input['password'] ?? '');

// Basic validation
if (!$email || !$password) {
    echo json_encode(["success" => false, "message" => "Email and password are required."]);
    exit;
}

$user = authenticateUser($email, $password);

if ($user) {
    session_start();
    $_SESSION['user'] = $user;
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid email or password."]);
}
?>
