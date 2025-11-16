<?php

require_once __DIR__ . '/../../config.php';

class applicationsModel{
    public static function getJobByID ($jobID){
        global $pdo;
        $sql = "SELECT j.title, j.description
                FROM jobs j
                WHERE j.job_id = :jobID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':jobID', $jobID);
        $stmt->execute();

        $result = $stmt->fetch();
        if($result){
            return $result;
        }else{
            return null;
        }
    }
    public static function getAllApplicationsForJobID ($jobID){
        global $pdo;
        $sql = "SELECT P.name AS Applicant_Name, P.email AS Applicant_Email
                FROM application A
                JOIN people P ON A.peopleRSN= P.peopleRSN
                JOIN jobs J ON A.job_id = J.job_id
                JOIN org O ON J.org_id = O.org_id
                WHERE A.job_id = :jobID;";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':jobID', $jobID);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if($result){
            return $result;
        }else{
            return null;
        }
    }

    public static function getApplicationsByPersonName ($name){
        global $pdo;
        $searchName = "%".$name."%";
        $sql = "SELECT P.name AS Applicant_Name, P.email AS Applicant_Email
                FROM application A
                JOIN people P ON A.peopleRSN = P.peopleRSN
                JOIN jobs J ON A.job_id = J.job_id
                LEFT JOIN org O ON J.org_id = O.org_id
                WHERE P.name LIKE :searchName";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':searchName', $searchName);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if($result){
            return $result;
        }else{
            return null;
        }
    }

    public static function getApplicationsByUserIdAndJobID($userID, $jobID){
        global $pdo;
        $sql = "SELECT P.name AS Applicant_Name, P.email AS Applicant_Email
                FROM application A
                JOIN people P ON A.peopleRSN = P.peopleRSN
                JOIN jobs J ON A.job_id = J.job_id
                WHERE A.peopleRSN = :userID AND J.job_id = :jobID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':jobID', $jobID);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if($result){
            return $result;
        }else{
            return null;
        }
    }
}

?>