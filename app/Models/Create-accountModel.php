<?php
// model/UserModel.php

class UserModel {
    private $dataFile;

    public function __construct() {
        // Save JSON file inside "data" folder
        $this->dataFile = __DIR__ . '/../data/users.json';

        // Create folder/file if missing
        if (!file_exists(dirname($this->dataFile))) {
            mkdir(dirname($this->dataFile), 0777, true);
        }
        if (!file_exists($this->dataFile)) {
            file_put_contents($this->dataFile, json_encode([]));
        }
    }

    public function createUser($name, $email, $phone, $password, $about, $skills) {
        $users = json_decode(file_get_contents($this->dataFile), true);

        // Prevent duplicate email
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return ["success" => false, "error" => "Email already exists"];
            }
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $newUser = [
            "id" => uniqid(),
            "name" => $name,
            "email" => $email,
            "phone" => $phone,
            "password" => $hashedPassword,
            "about" => $about,
            "skills" => $skills,
            "created_at" => date("Y-m-d H:i:s")
        ];

        $users[] = $newUser;

        file_put_contents($this->dataFile, json_encode($users, JSON_PRETTY_PRINT));

        return ["success" => true, "user" => $newUser];
    }
}
?>
