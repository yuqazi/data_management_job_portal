<?php

require_once __DIR__ . '/../../config.php';

function authenticateUser($email, $password) {
    /*
    $users = [
        [
            "email" => "admin@techcorp.com",
            "password" => "admin123",
            "role" => "employer"
        ],
        [
            "email" => "user@jobportal.com",
            "password" => "user123",
            "role" => "applicant"
        ]
    ];

    foreach ($users as $user) {
        if ($user['email'] === $email && $user['password'] === $password) {
            return [
                "email" => $user['email'],
                "role" => $user['role']
            ];
        }
    }
    */
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    global $pdo;

    // SQL query to fetch user while org has no password field
    $sql = "SELECT 	p.personRSN AS userID,
                p.email AS email, 
	            p.password AS password,
	            CASE 
		            WHEN o.email IS NOT NULL THEN TRUE
        	        ELSE FALSE
    	        END AS role
            FROM people p
            LEFT JOIN org o ON p.email = o.email
            WHERE p.email = :email;";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && $user['email'] === $email && password_verify($password, $user['password'])) {
        if (count($user) > 1) {
            error_log("Multiple users found with the same email: " . $email);
            return null;
        }
        return [
            "userID" => $user['userID'],
            "role" => $user['role'] ? 'employer' : 'applicant'
        ];
    }
    error_log("Incorrect email or password for email: " . $email);
    return null;
}
?>
