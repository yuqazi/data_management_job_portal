<?php
// models/UserModel.php

class UserModel {

    // Simulated user database
    private static $users = [
        1 => [
            'id' => 1,
            'name' => 'John Doe',
            'description' => 'I am a Software Engineer located on Earth and I am a fun guy :D',
            'email' => 'example@email.net',
            'phone' => '123-456-7890',
            'skills' => ['HTML', 'CSS', 'JavaScript', 'Python', "Communication", "Word", "Excel", "Chicken"]
        ],
        2 => [
            'id' => 2,
            'name' => 'Jane Doe',
            'description' => 'I am a fun guy :D located on Earth and I am a Software Engineer',
            'email' => 'email@example.com',
            'phone' => '123-123-1234',
            'skills' => ['Example', 'Another Example', 'Yet Another Example', "One More Example", "I'm out of examples"]
        ]
    ];

    // Fetch user by ID
    public static function getUser($userId = 1) {
        // Return the user if exists, otherwise return null
        return self::$users[$userId] ?? null;
    }
}
?>
