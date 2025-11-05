<?php
// indexController.php
header('Content-Type: application/json');
require_once 'indexModel.php';

// Get pagination params
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5; // default 5 per page
if ($page < 1) $page = 1;

// Fetch all jobs (later you’ll do this from the DB)
$allJobs = getAllJobs();

// Compute offset and slice jobs
$offset = ($page - 1) * $limit;
$pagedJobs = array_slice($allJobs, $offset, $limit);

// Include total count for frontend
$response = [
    "page" => $page,
    "limit" => $limit,
    "totalJobs" => count($allJobs),
    "jobs" => $pagedJobs
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>
