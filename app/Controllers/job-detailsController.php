<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../Models/job-detailsModel.php';


// Get job_id from the query string (e.g., job-detailsController.php?job_id=5)
$jobId = $_GET['job_id'] ?? null;

if (!$jobId) {
    echo json_encode([
        'success' => false,
        'error' => 'Missing job_id parameter.'
    ]);
    exit;
}

// Get job details from the model
$jobDetailsModel = new jobDetailsModel();
$jobDetails = $jobDetailsModel->getJobDetailsById($jobId);
// Return as JSON
if ($jobDetails) {
    echo json_encode([
        'success' => true,
        'jobDetails' => $jobDetails
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Job not found for the given ID.'
    ]);
}

?>
