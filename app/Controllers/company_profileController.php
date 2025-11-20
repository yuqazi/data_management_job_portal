<?php
header('Content-Type: application/json');

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    file_put_contents(__DIR__ . '/error.log', date('Y-m-d H:i:s') . " - $errstr in $errfile:$errline\n", FILE_APPEND);
});

require_once __DIR__ . '/../Models/company_profileModel.php';

$companyId = isset($_POST['companyId']) ? intval($_POST['companyId']) : 1;

// Check what data is being requested
$requestType = isset($_POST['type']) ? $_POST['type'] : 'company';

// Allow explicit actions (remove, export)
$action = isset($_POST['action']) ? $_POST['action'] : null;

if ($action === 'remove') {
    $jobId = isset($_POST['jobId']) ? intval($_POST['jobId']) : 0;
    if ($jobId > 0) {
        $deleted = false;
        try {
            $deleted = company_profileModel::DeleteJob($jobId);
        } catch (Throwable $e) {
            error_log("Error deleting job ID $jobId: " . $e->getMessage());
        }
        echo json_encode(['success' => (bool)$deleted]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid job id']);
    }
    exit;
}

if ($action === 'export') {
    $jobId = isset($_POST['jobId']) ? intval($_POST['jobId']) : 0;
    if ($jobId > 0) {
        // model will output CSV headers and content directly
        company_profileModel::exportApplications($companyId, $jobId);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid job id']);
    }
    exit;
}

try {
    if ($requestType === 'jobs') {
        // Fetch jobs for the company
        $jobs = company_profileModel::getJobsByCompany($companyId);
        if ($jobs) {
            // getJobsByCompany returns a single row, but we need an array
            echo json_encode(is_array($jobs) && isset($jobs[0]) ? $jobs : [$jobs]);
        } else {
            echo json_encode([]);
        }
    } else {
        // Fetch company info
        $company = company_profileModel::getCompany($companyId);
        
        if ($company) {
            echo json_encode($company);
        } else {
            echo json_encode(['error' => 'Company not found']);
        }
    }
} catch (Exception $e) {
    file_put_contents(__DIR__ . '/error.log', date('Y-m-d H:i:s') . " - Exception: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>