<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../Models/applicationsModel.php';

$model = new applicationsModel();

// Accept multiple param names to avoid breakage
$jobId = $_GET['jobRSN'] 
      ?? $_GET['job_id'] 
      ?? $_GET['id'] 
      ?? null;

if (!$jobId || !is_numeric($jobId)) {
    echo json_encode([
        "success" => false,
        "error" => "Missing or invalid job identifier."
    ]);
    exit;
}

$jobId = intval($jobId);

// Get job info
$job = $model->getJobById($jobId);

// Get applications
$applications = $model->getApplicationsByJobRSN($jobId);

// Output final JSON
echo json_encode([
    "success" => true,
    "job" => $job,
    "applications" => $applications
], JSON_PRETTY_PRINT);
