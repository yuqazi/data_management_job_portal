<?php
require_once __DIR__ . '/../../config.php';

if (!isset($pdo)) {
    die("ERROR: PDO is not set. Check config.php");
}

function authenticateUser($email, $password)
{
    global $pdo;

    // Prepare SQL statement
    $stmt = $pdo->prepare("
        SELECT 
            p.people_id AS userID,
            p.email,
            p.password,
            CASE 
                WHEN o.email IS NOT NULL THEN 'employer'
                ELSE 'applicant'
            END AS role
        FROM people p
        LEFT JOIN org o ON p.email = o.email
        WHERE p.email = :email
        LIMIT 1
    ");

    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) return false;

    // Plain-text password comparison (only if DB passwords are not hashed)
    if (!password_verify($password, $user['password'])) return false;

    return $user;
}
