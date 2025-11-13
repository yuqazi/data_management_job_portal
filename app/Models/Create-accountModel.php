<?php
// model/UserModel.php
require_once ('../../config.php');

class UserModel {
    public static function createUser($name, $email, $phone, $password, $about, $skills): bool
    {
        global $pdo;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, phone, password, about, skills)"
            . "VALUES (:name, :email, :phone, :password, :about, :skills)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':about', $about);
        $stmt->bindParam(':skills', $skills);

        try{
            $stmt->execute();
            return true;
        }catch(PDOException $e){
            error_log($e->getMessage());
            return false;
        }
    }
}
?>
