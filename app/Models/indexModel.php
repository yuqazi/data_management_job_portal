<?php
// indexModel.php
require_once __DIR__ . '/../../config.php'; // make sure config.php is in /var/www/html/

/**
 * Fetch all jobs with company name, location, description, job type, experience, and salary.
 */
function getAllJobs() {
    global $pdo;

    $sql = "
        SELECT 
            j.title,
            o.name AS company,
            j.location,
            j.description,
            j.job_type AS jobType,
            e.yearrange AS experience,
            j.pay AS salaryRange
        FROM jobs j
        JOIN org o ON j.org_id = o.org_id
        LEFT JOIN exp_want e ON e.job_id = j.job_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Fetch top skills and number of jobs requiring each skill.
 * Adjust column names if your schema differs.
 */
function getSkillsGraph() {
    global $pdo;

    $sql = "
        SELECT 
            s.skill AS skill,
            COUNT(sh.people_id) AS people_count
        FROM skills s
        JOIN skills_has sh ON sh.skill_id = s.skill_id
        GROUP BY s.skill
        ORDER BY people_count DESC
        LIMIT 5
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
