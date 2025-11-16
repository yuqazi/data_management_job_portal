<?php
require_once __DIR__ . '/../../config.php';

class OrgModel {

    public static function createOrg($name, $phone, $email, $location)
    {
        global $pdo;

        // Check if email already exists
        $sqlCheck = "SELECT * FROM org WHERE email = :email";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([':email' => $email]);

        if ($stmtCheck->rowCount() > 0) {
            return ['success' => false, 'error' => 'Email already exists'];
        }

        // Insert into org table
        $sql = "INSERT INTO org (name, telephone, email, location)
                VALUES (:name, :telephone, :email, :location)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':telephone' => $phone,
            ':email' => $email,
            ':location' => $location
        ]);

        $orgId = $pdo->lastInsertId();

        return [
            'success' => true,
            'org_id' => $orgId
        ];
    }
}
?>
