<?php
// models/UserModel.php

class UserModel
{

    // Simulated user database
    private static $users = [
        1 => [
            'id' => 1,
            'name' => 'John Doe',
            'description' => 'I am a Software Engineer located on Earth and I am a fun guy :D',
            'email' => 'example@email.net',
            'phone' => '123-456-7890',
            'skills' => ['HTML', 'CSS', 'JavaScript', 'Python', "Communication", "Word", "Excel", "Chicken"],
            'jobs' => [
                [
                    'title' => 'Software Engineer',
                    'company' => 'Tech Corp',
                    'duration' => '2018 - Present',
                    'description' => 'Developing web applications and services.'
                ],
                [
                    'title' => 'Junior Developer',
                    'company' => 'Web Solutions',
                    'duration' => '2016 - 2018',
                    'description' => 'Assisted in the development of client websites.'
                ]
            ]
        ],
        2 => [
            'id' => 2,
            'name' => 'Jane Doe',
            'description' => 'I am a fun guy :D located on Earth and I am a Software Engineer',
            'email' => 'email@example.com',
            'phone' => '123-123-1234',
            'skills' => ['Example', 'Another Example', 'Yet Another Example', "One More Example", "I'm out of examples"],
            'jobs' => [
                [
                    'title' => 'Example Title',
                    'company' => 'Example Company',
                    'duration' => '2020 - Present',
                    'description' => 'This is an example job description.'
                ],
                [
                    'title' => 'Another Title',
                    'company' => 'Another Company',
                    'duration' => '2018 - 2020',
                    'description' => 'This is another example job description.'
                ],
                [
                    'title' => 'Old Title',
                    'company' => 'Old Company',
                    'duration' => '2016 - 2018',
                    'description' => 'This is an old example job description.'
                ]
                ],
            'certifications' => [
                [
                    'name' => 'Certified Example Professional',
                    'issuer' => 'Example Institute',
                    'year' => 2019
                ],
                [
                    'name' => 'Advanced Example Specialist',
                    'issuer' => 'Another Institute',
                    'year' => 2021
                ]
                ]
            ],
    ];

    // Fetch user by ID
    public static function getUser($userId = 1)
    {
        // Return the user if exists, otherwise return null
        return self::$users[$userId] ?? null;
    }
}
