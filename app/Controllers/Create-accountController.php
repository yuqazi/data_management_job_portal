<?php
// controller/CreateAccountController.php
// Require model using correct relative path from this controller
require_once __DIR__ . '/../Models/Create-accountModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode(["success" => false, "error" => "No data received"]);
        exit;
    }

    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? '';
    $password = $data['password'] ?? '';
    $about = $data['about'] ?? '';
    $skills = $data['skills'] ?? [];

    $userModel = new UserModel();
    $result = $userModel->createUser($name, $email, $phone, $password, $about, $skills);

    echo json_encode($result);
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
}
?>
