<?php
// app/Controllers/orgCreate-accountController.php
require_once __DIR__ . '/../Models/orgCreate-accountModel.php';

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
    $address = $data['address'] ?? '';

    $orgModel = new OrgModel();
    $result = $orgModel->createOrg($name, $email, $phone, $password, $address);

    echo json_encode($result);
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
}
?>
