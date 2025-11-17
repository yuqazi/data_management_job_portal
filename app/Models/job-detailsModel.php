<?php
require_once __DIR__ . '/../../config.php';

class JobModel {
    public static function getJobDetailsById($jobId) {
        global $pdo;

        // Fetch job info
        $stmt = $pdo->prepare("SELECT * FROM jobs WHERE job_id = :job_id LIMIT 1");
        $stmt->execute([':job_id' => $jobId]);
        $job = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$job) return null;

        // Fetch skills wanted for this job
        $stmtSkills = $pdo->prepare("
            SELECT s.skill 
            FROM skills_want sw
            INNER JOIN skills s ON sw.skill_id = s.skill_id
            WHERE sw.job_id = :job_id
        ");
        $stmtSkills->execute([':job_id' => $jobId]);
        $skills = $stmtSkills->fetchAll(PDO::FETCH_COLUMN);

        $job['tags'] = $skills;

        return $job;
    }
}
