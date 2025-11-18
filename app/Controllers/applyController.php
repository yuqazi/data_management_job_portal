<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../Models/applyModel.php';

header("Content-Type: application/json");

if (!isset($_GET['job_id'])) {
    echo json_encode(["success" => false, "message" => "job_id missing"]);
    exit;
}

$jobId = intval($_GET['job_id']);
$model = new ApplyModel();
$data = $model->getJobAndQuestions($jobId);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Job not found"]);
    exit;
}

echo json_encode(["success" => true, "data" => $data]);
exit;
