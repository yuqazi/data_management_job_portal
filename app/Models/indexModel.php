<?php
require_once __DIR__ . '/../../config.php';

function getJobsFiltered($filters, $page, $limit, $search, $location) {
    global $pdo;

    $where = [];
    $params = [];

    // ---------------------------------------------
    // 🔍 SEARCH term filtering
    // ---------------------------------------------
    if (!empty($search)) {
        $where[] = "(j.title LIKE ? OR j.description LIKE ? OR o.name LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    // Location search
    if (!empty($location)) {
        $where[] = "j.location LIKE ?";
        $params[] = "%$location%";
    }

    // ---------------------------------------------
    // Job type filter (checkbox)
    // ---------------------------------------------
    $jobTypes = $filters['job_type'] ?? null;
    if ($jobTypes) {
        $where[] = "j.job_type = ?";
        $params[] = $jobTypes;
    }

    // ---------------------------------------------
    // Salary filter (checkboxes)
    // ---------------------------------------------
    if (isset($filters['salary'])) {
        $salary = $filters['salary'];
        switch ($salary) {
            case '<40k':      $where[] = "j.pay < 40000"; break;
            case '40-60k':    $where[] = "j.pay BETWEEN 40000 AND 60000"; break;
            case '60-80k':    $where[] = "j.pay BETWEEN 60000 AND 80000"; break;
            case '80-100k':   $where[] = "j.pay BETWEEN 80000 AND 100000"; break;
            case '100k+':     $where[] = "j.pay >= 100000"; break;
        }
    }

    // ---------------------------------------------
    // Build final SQL
    // ---------------------------------------------
    $whereSQL = empty($where) ? "" : "WHERE " . implode(" AND ", $where);

    $sql = "
        SELECT 
            j.job_id,
            j.title,
            j.description,
            j.location,
            j.pay,
            j.job_type,
            o.name AS company
        FROM jobs j
        LEFT JOIN org o ON o.org_id = j.org_id
        $whereSQL
    ";

    // Get all matching rows
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $allRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Pagination
    $totalJobs = count($allRows);
    $offset = ($page - 1) * $limit;
    $paged = array_slice($allRows, $offset, $limit);

    return [
        "page" => $page,
        "limit" => $limit,
        "totalJobs" => $totalJobs,
        "jobs" => $paged
    ];
}
