<?php
class TagModel {
    private $tags;

    public function __construct() {
        // Hardcoded tags for now; later can fetch from DB
        $this->tags = [
            "JavaScript",
            "Python",
            "SQL",
            "HTML",
            "CSS",
            "React",
            "Node.js"
        ];
    }

    public function getTags() {
        return $this->tags;
    }
}
?>
