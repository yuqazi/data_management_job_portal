<?php

require_once __DIR__ . '/../../config.php';

function createJob($title, $description, $location, $salary, $companyId, $jobType, $hours)
{
    global $pdo;

    $sql = "INSERT INTO jobs (title, description, location, pay, org_id, job_type, hours)
            VALUES (:title, :description, :location, :salary, :org_id, :job_type, :hours);";
    
    try {
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':salary', $salary);
        $stmt->bindParam(':org_id', $companyId);
        $stmt->bindParam(':job_type', $jobType);
        $stmt->bindParam(':hours', $hours);

        $stmt->execute();
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Database error in createJob: " . $e->getMessage());
        throw $e;
    }
}
