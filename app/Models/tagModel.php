<?php

require_once __DIR__ . '/../../config.php';

class TagModel {
    private $tags;

    public function __construct() {

        global $pdo;

        $sql = "SELECT name FROM skills GROUP BY name ORDER BY COUNT(*) DESC;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $this->tags = $stmt->fetchAll(PDO::FETCH_COLUMN);


        // Hardcoded tags for now; later can fetch from DB
        /*
        $this->tags = [
            "JavaScript",
            "Python",
            "SQL",
            "HTML",
            "CSS",
            "React",
            "Node.js"
        ];
        */
    }

    public function getTags() {
        return $this->tags;
    }
}
?>
