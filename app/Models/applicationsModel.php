<?php
require_once __DIR__ . '/../../config.php';

class applicationsModel {

    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // Get job info (title + description)
    public function getJobById($jobId) {
        $sql = "
            SELECT job_id, title, description, location, pay, org_id
            FROM jobs
            WHERE job_id = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$jobId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all applications for a job
    public function getApplicationsByJobRSN($jobId) {
        $sql = "
            SELECT 
                a.people_id AS user_id,
                p.name,
                p.email,
                a.applied_at
            FROM application a
            JOIN people p ON p.people_id = a.people_id
            WHERE a.job_id = ?
            ORDER BY a.applied_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$jobId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
