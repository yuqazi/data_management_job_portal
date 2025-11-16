<?php

require_once __DIR__ . '/../../config.php';

function createJob($title, $description, $location, $salary, $companyId, $jobType)
{
    global $pdo;

    $sql = "INSERT INTO jobs (title, desc, location, pay, org_id, job_type)
            VALUES (:title, :description, :location, :salary, :org_id, :job_type);";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':salary', $salary);
    $stmt->bindParam(':org_id', $companyId);
    $stmt->bindParam(':job_type', $jobType);

    try {
        $stmt->execute();
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return null;
    }
}
