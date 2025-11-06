<?php
function authenticateUser($email, $password) {
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
    return null;
}
?>
