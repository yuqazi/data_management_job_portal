<?php
// models/UserModel.php

require_once __DIR__ . '/../../config.php';

class UserModel
{
    public static function getUser($userId)
    {
        global $pdo;
        
        // Fetch basic user info
        $sql = "SELECT name, email, telephone, about, address
                FROM people
                WHERE people_id = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return null;
        }

        // Fetch skills
        $sqlSkills = "SELECT s.skill
                      FROM skills s
                      JOIN skills_has sh ON s.skill_id = sh.skill_id
                      WHERE sh.people_id = :userId";
        $stmtSkills = $pdo->prepare($sqlSkills);
        $stmtSkills->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmtSkills->execute();
        $skills = $stmtSkills->fetchAll(PDO::FETCH_COLUMN);

        // Fetch work experience
        $sqlwork = "SELECT w.title AS title, w.duration AS duration
                FROM work_exp w
                WHERE w.people_id = :userId";
        $stmtWork = $pdo->prepare($sqlwork);
        $stmtWork->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmtWork->execute();
        $work = $stmtWork->fetchAll(PDO::FETCH_ASSOC);

        $sqlcertifications = "SELECT c.certificate AS certificate
                    FROM certificates c
                    WHERE c.people_id = :userId";
        $stmtCertifications = $pdo->prepare($sqlcertifications);
        $stmtCertifications->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmtCertifications->execute();
        $certifications = $stmtCertifications->fetchAll(PDO::FETCH_ASSOC);

        // Combine all data into a single return structure
        return [
            'id' => $userId,
            'name' => $user['name'] ?? '',
            'email' => $user['email'] ?? '',
            'phone' => $user['telephone'] ?? '',
            'description' => $user['about'] ?? '',
            'address' => $user['address'] ?? '',
            'skills' => $skills,
            'jobs' => $work,
            'certifications' => $certifications
        ];
    }

    public static function updateAbout($userId, $about)
    {
        global $pdo;
        $sql = "UPDATE people
                SET about = :about
                WHERE people_id = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':about', $about);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Add a new work experience entry
    public static function addWorkExperience($userId, $title, $duration)
    {
        global $pdo;
        $sql = "INSERT INTO work_exp (people_id, title, duration)
                VALUES (:userId, :title, :duration)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':duration', $duration);
        return $stmt->execute();
    }

    // Add a new certification entry
    public static function addCertification($userId, $certificate)
    {
        global $pdo;
        $sql = "INSERT INTO certificates (people_id, certificate)
                VALUES (:userId, :certificate)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':certificate', $certificate);
        return $stmt->execute();
    }

    // Add a new skill
    public static function addSkill($userId, $skill)
    {
        global $pdo;
        $sql = "INSERT INTO skills_has (people_id, skill_id)
                VALUES (:userId, :skill)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':skill', $skill);
        return $stmt->execute();
    }
}
?>
