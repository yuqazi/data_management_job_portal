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

    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $phone = preg_replace('/\D/', '', $data['phone'] ?? '');
    $location = trim($data['location'] ?? '');

    // Validation
    $errors = [];
    if ($name === '') $errors[] = "Organization name is required.";
    if ($email === '') $errors[] = "Email is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
    if (strlen($phone) !== 10) $errors[] = "Phone must be 10 digits.";
    if ($location === '') $errors[] = "Location is required.";

    if (!empty($errors)) {
        echo json_encode(["success" => false, "error" => implode(' ', $errors)]);
        exit;
    }

    // Create org
    $result = OrgModel::createOrg($name, $phone, $email, $location);

    echo json_encode($result);
    exit;

} else {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
}
?>
