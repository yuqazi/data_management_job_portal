<?php
// Start output buffering to catch any stray output
ob_start();

// Set error handler to prevent HTML output on errors
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $errstr
    ]);
    exit;
});

// Set exception handler
set_exception_handler(function($exception) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $exception->getMessage()
    ]);
    exit;
});

header('Content-Type: application/json');
require_once __DIR__ . '/../Models/create_jobModel.php';
require_once __DIR__ . '/../../config.php';

// Clear any buffered output from includes
ob_end_clean();

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
    exit;
}

// Get the current user's org_id from session
session_start();

// Extract org_id from session user if available, otherwise use default
$companyId = 1; // default
if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
    $companyId = $_SESSION['user']['org_id'] ?? $_SESSION['user']['orgId'] ?? 1;
}

// Get raw JSON data
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

// Validate JSON payload
if (!$data) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON payload.'
    ]);
    exit;
}

// Validate required fields
if (!isset($data['title']) || !isset($data['description']) || !isset($data['location']) || !isset($data['jobType'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Missing required fields'
    ]);
    exit;
}

// Extract and sanitize inputs
$title = trim($data['title'] ?? '');
$description = trim($data['description'] ?? '');
$location = trim($data['location'] ?? '');
$salary = isset($data['salary']) ? trim($data['salary']) : null;
$jobType = trim($data['jobType'] ?? '');
$hours = isset($data['hours']) ? trim($data['hours']) : null;

// Debug logging
error_log("CreateJob Debug - Title: $title, Description: $description, Location: $location, Salary: $salary, JobType: $jobType, CompanyId: $companyId");

// Create the job
try {
    $jobId = createJob($title, $description, $location, $salary, $companyId, $jobType, $hours);

    if ($jobId) {
        echo json_encode([
            'success' => true,
            'message' => 'Job created successfully!',
            'jobId' => $jobId
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to create job. Please try again.'
        ]);
    }
} catch (Exception $e) {
    error_log("CreateJob Exception: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
