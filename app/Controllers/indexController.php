<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../Models/indexModel.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;

$search = $_GET['search'] ?? "";
$location = $_GET['location'] ?? "";

$filters = $_GET; // pass all filters to model

$result = getJobsFiltered($filters, $page, $limit, $search, $location);

echo json_encode($result, JSON_PRETTY_PRINT);
