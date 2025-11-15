<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../Models/applicationsModel.php';

// include a function to get applications by user ID
$userId = $_GET['user_id'] ?? null;
$jobId = $_GET['job_id'] ?? null;
if (!$userId) {
    echo json_encode([
        'success' => false,
        'error' => 'Missing user_id parameter.'
    ]);
    exit;
}

// Get applications from the model
$applicationsModel = new applicationsModel();
$applications = $applicationsModel->getApplicationsByUserIdAndJobID($userId, $jobId);

// Return as JSON
if ($applications !== null) {
    echo json_encode([
        'success' => true,
        'applications' => $applications
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'No applications found for the given user.'
    ]);
}

?>
