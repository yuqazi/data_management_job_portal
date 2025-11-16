<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Load the UserModel
require_once __DIR__ . '/../Models/Create-accountModel.php';

// Get raw POST JSON
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

// Validate JSON payload
if (!$data) {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid JSON payload.'
    ]);
    exit;
}

// Extract and sanitize fields
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$phone = preg_replace('/\D/', '', $data['phone'] ?? '');
$password = $data['password'] ?? '';
$about = trim($data['about'] ?? '');
$skills = $data['skills'] ?? [];

// Server-side validation
$errors = [];
if ($name === '') $errors[] = 'Name is required.';
if ($email === '') $errors[] = 'Email is required.';
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';
if (strlen($phone) !== 10) $errors[] = 'Phone must be 10 digits.';
if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
if ($about === '') $errors[] = 'About is required.';

// If there are validation errors, return them
if (!empty($errors)) {
    echo json_encode([
        'success' => false,
        'error' => implode(' ', $errors)
    ]);
    exit;
}

// Create user using the model
$result = UserModel::createUser($name, $email, $phone, $password, $about, $skills);

// Return JSON response based on model result
if ($result['success'] ?? false) {
    echo json_encode([
        'success' => true,
        'message' => 'Account created successfully.',
        'userId' => $result['userId'] ?? null
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => $result['error'] ?? 'Failed to create account. A database error occurred.'
    ]);
}
