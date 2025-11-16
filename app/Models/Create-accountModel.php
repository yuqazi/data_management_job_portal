<?php
require_once __DIR__ . '/../../config.php';

class UserModel {
    public static function createUser($name, $email, $phone, $password, $about, $skills)
    {
        global $pdo;

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if email exists
        $sqlcheck = "SELECT * FROM people WHERE email = :email";
        $stmtcheck = $pdo->prepare($sqlcheck);
        $stmtcheck->execute([':email' => $email]);

        if ($stmtcheck->rowCount() > 0) {
            return ['success' => false, 'error' => 'Email already exists'];
        }

        // Insert into people
        $sql = "INSERT INTO people (name, email, telephone, password, about)
                VALUES (:name, :email, :telephone, :password, :about)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':telephone' => $phone,
            ':password' => $hashedPassword,
            ':about' => $about
        ]);

        $userId = $pdo->lastInsertId();

// Insert skills into skills_has
$sqlSkillLookup = "SELECT skill_id FROM skills WHERE skill = :skill LIMIT 1";
$stmtSkillLookup = $pdo->prepare($sqlSkillLookup);

$sqlInsertSkill = "INSERT INTO skills_has (skill_id, people_id)
                   VALUES (:skill_id, :people_id)";
$stmtInsertSkill = $pdo->prepare($sqlInsertSkill);

foreach ($skills as $skill) {

    // Look up the skill_id based on the skill name
    $stmtSkillLookup->execute([':skill' => $skill]);
    $skillRow = $stmtSkillLookup->fetch(PDO::FETCH_ASSOC);

    if ($skillRow) {
        // Insert relationship into skills_has
        $stmtInsertSkill->execute([
            ':skill_id'  => $skillRow['skill_id'],
            ':people_id' => $userId
        ]);
    }
}


        return ['success' => true, 'userId' => $userId];
    }
}
?>
