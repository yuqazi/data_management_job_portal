<?php
// app/Models/orgCreate-accountModel.php

class OrgModel {
    private $dataFile;

    public function __construct() {
        // Save JSON file inside "data" folder
        $this->dataFile = __DIR__ . '/../data/orgs.json';

        // Create folder/file if missing
        if (!file_exists(dirname($this->dataFile))) {
            mkdir(dirname($this->dataFile), 0777, true);
        }
        if (!file_exists($this->dataFile)) {
            file_put_contents($this->dataFile, json_encode([]));
        }
    }

    public function createOrg($name, $email, $phone, $password, $address) {
        $orgs = json_decode(file_get_contents($this->dataFile), true);

        // Prevent duplicate email
        foreach ($orgs as $o) {
            if ($o['email'] === $email) {
                return ["success" => false, "error" => "Email already exists"];
            }
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $newOrg = [
            "id" => uniqid(),
            "name" => $name,
            "email" => $email,
            "phone" => $phone,
            "password" => $hashedPassword,
            "address" => $address,
            "created_at" => date("Y-m-d H:i:s")
        ];

        $orgs[] = $newOrg;

        file_put_contents($this->dataFile, json_encode($orgs, JSON_PRETTY_PRINT));

        return ["success" => true, "org" => $newOrg];
    }
}
?>
