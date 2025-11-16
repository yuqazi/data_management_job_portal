<?php
class jobDetailsModel{
    public static function getJobDetailsById($jobId){
        global $pdo;
        $sql = "SELECT j.title, j.description, j.location, j.pay, j.location, j.pay, j.hours, s.skill, s.skillgroup
                FROM jobs j
                JOIN skills_want w ON j.jobRSN = w.jobRSN
                JOIN skills s ON s.skillRSN = w.skillRSN
                WHERE j.jobRSN = :jobId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':jobId', $jobId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            return $result;
        }else{
            return null;
        }
    }
    public static function getOrgIdByJobId($jobId){
        global $pdo;
        $sql = "SELECT j.orgRSN
                FROM jobs j
                WHERE j.jobRSN = :jobId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':jobId', $jobId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            return $result;
        }else{
            return null;
        }
    }
}