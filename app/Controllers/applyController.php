<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../Models/applyModel.php';

// Get job_id from the query string (e.g., applyController.php?job_id=5)
$jobId = $_GET['job_id'] ?? null;

if (!$jobId) {
    echo json_encode([
        'success' => false,
        'error' => 'Missing job_id parameter.'
    ]);
    exit;
}


// Get job and questions from the model
$dataModel = new applyModel();
$data = $dataModel->getJobWithQuestions($jobId);

// Return as JSON
if ($data) {
    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Job not found or no questions available.'
    ]);
}
?>
