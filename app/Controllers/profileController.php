<?php
header('Content-Type: application/json');
// Require the profile model using a correct relative path from this controller
require_once __DIR__ . '/../Models/profileModel.php';

// Get the user ID from query string (default to 1)
$userId = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Fetch the user from the model
$user = UserModel::getUser($userId);

if ($user) {
    echo json_encode($user);
} else {
    echo json_encode(['error' => 'User not found']);
}
?>
