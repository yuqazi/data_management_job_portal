<?php
header('Content-Type: application/json');
// Correct relative path: from app/Controllers to app/Models is ../Models
require_once __DIR__ . '/../Models/indexModel.php';

// Pagination params
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
if ($page < 1) $page = 1;

// Fetch all jobs
$allJobs = getAllJobs();

// --- FILTERS ---

// Job Type
$jobTypes = [];
foreach (['fulltime','parttime','remote','internship','volunteer'] as $key) {
    if (isset($_GET[$key])) {
        switch($key){
            case 'fulltime': $jobTypes[] = 'Full-Time'; break;
            case 'parttime': $jobTypes[] = 'Part-Time'; break;
            case 'remote': $jobTypes[] = 'Remote'; break;
            case 'internship': $jobTypes[] = 'Internship'; break;
            case 'volunteer': $jobTypes[] = 'Volunteer'; break;
        }
    }
}
if (!empty($jobTypes)) {
    $allJobs = array_filter($allJobs, function($job) use ($jobTypes) {
        return in_array($job['jobType'], $jobTypes);
    });
}

// Experience Level
$experienceLevels = [];
foreach (['entry','mid','senior'] as $key) {
    if (isset($_GET[$key])) {
        switch($key){
            case 'entry': $experienceLevels[] = 'Entry-Level'; break;
            case 'mid': $experienceLevels[] = 'Mid-Level'; break;
            case 'senior': $experienceLevels[] = 'Senior-Level'; break;
        }
    }
}
if (!empty($experienceLevels)) {
    $allJobs = array_filter($allJobs, function($job) use ($experienceLevels) {
        return in_array($job['experience'], $experienceLevels);
    });
}

// Salary Range
$salaryRanges = [];
foreach (['s40','s60','s80','s100','s100plus'] as $key) {
    if (isset($_GET[$key])) {
        switch($key){
            case 's40': $salaryRanges[] = '<40k'; break;
            case 's60': $salaryRanges[] = '40k-60k'; break;
            case 's80': $salaryRanges[] = '60k-80k'; break;
            case 's100': $salaryRanges[] = '80k-100k'; break;
            case 's100plus': $salaryRanges[] = '100k+'; break;
        }
    }
}
if (!empty($salaryRanges)) {
    $allJobs = array_filter($allJobs, function($job) use ($salaryRanges) {
        return in_array($job['salaryRange'], $salaryRanges);
    });
}

// Pagination
$allJobs = array_values($allJobs); // reindex after filtering
$offset = ($page - 1) * $limit;
$pagedJobs = array_slice($allJobs, $offset, $limit);

// --- Add job_id and org_id by fetching from the DB directly ---
global $pdo;
foreach ($pagedJobs as &$job) {
    $stmt = $pdo->prepare("SELECT job_id, org_id FROM jobs WHERE title = :title AND description = :description LIMIT 1");
    $stmt->execute([
        'title' => $job['title'],
        'description' => $job['description']
    ]);
    $ids = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($ids) {
        $job['job_id'] = $ids['job_id'];
        $job['org_id'] = $ids['org_id'];
    }
}
unset($job);

// Response
$response = [
    "page" => $page,
    "limit" => $limit,
    "totalJobs" => count($allJobs),
    "jobs" => $pagedJobs,
];

echo json_encode($response, JSON_PRETTY_PRINT);
