<?php

require_once __DIR__ . '/../../config.php';

class TagModel {
    private $tags;

    public function __construct() {

        global $pdo;

        $sql = "SELECT skill FROM skills GROUP BY skill ORDER BY COUNT(*) DESC;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $this->tags = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getTags() {
        return $this->tags;
    }
}
?>
