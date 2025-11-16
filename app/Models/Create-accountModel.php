<?php
// model/UserModel.php
require_once __DIR__ . '/../../config.php';

class UserModel {
    public static function createUser($name, $email, $phone, $password, $about, $skills)
    {
        global $pdo;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO people (name, email, phone, password, about)"
            . "VALUES (:name, :email, :phone, :password, :about);";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':about', $about);
        $stmt->bindParam(':skills', $skills);

        $sqlcheck = "SELECT * FROM people WHERE email = :email;";
        $stmtcheck = $pdo->prepare($sqlcheck);

        $stmtcheck->bindParam(':email', $email);

        $sqlskill = "INSERT INTO skills (people_id, skill) VALUES (:people_id, :skill);";
        $stmtskill = $pdo->prepare($sqlskill);


        try{
            $stmtcheck->execute();
            if($stmtcheck->rowCount() > 0){
                // Email already exists
                error_log("Email already exists: " . $email);
                return null;
            }
            $stmt->execute();
            $applicationId = $pdo->lastInsertId(); 

            foreach($skills as $skill){
                $stmtskill->bindParam(':skill', $skill);
                $stmtskill->bindParam(':people_id', $applicationId);
                $stmtskill->execute();
            }           
            return $applicationId;
        }catch(PDOException $e){
            error_log($e->getMessage());
            return null;
        }
    }
}
?>
