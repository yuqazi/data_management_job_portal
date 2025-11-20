<?php
session_start();
require_once __DIR__ . '/../Models/applyModel.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit;
}

if (!isset($_POST['job_id'])) {
    echo json_encode(["success" => false, "message" => "job_id missing"]);
    exit;
}

$userId = $_SESSION['user_id'];
$jobId = $_POST['job_id'];
$coverLetter = $_POST['cover_letter'] ?? '';
$answers = json_decode($_POST['answers'] ?? "{}", true);

// === File upload ===
$uploadDir = "/var/www/html/files/user_$userId";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true); // recursive
}

$resumePath = null;

if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {

    $filename = basename($_FILES['resume']['name']);
    $resumePath = "$uploadDir/$filename";

    if (!move_uploaded_file($_FILES['resume']['tmp_name'], $resumePath)) {
        echo json_encode(["success" => false, "message" => "File upload failed"]);
        exit;
    }
}

// === Save to DB ===
$model = new ApplyModel();
$applicationId = $model->saveApplication(
    $userId,
    $jobId,
    $coverLetter,
    $resumePath
);

if (!empty($answers)) {
    $model->saveApplicationAnswers($applicationId, $answers);
}

echo json_encode(["success" => true, "message" => "Application submitted"]);
