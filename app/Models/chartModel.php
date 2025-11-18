<?php

require_once __DIR__ . '/../../config.php';

function getSkillsGraph() {
    global $pdo;

    $sql = "
        SELECT 
            s.skill AS skill,
            COUNT(sh.people_id) AS people_count
        FROM skills s
        JOIN skills_has sh ON sh.skill_id = s.skill_id
        GROUP BY s.skill
        ORDER BY people_count DESC
        LIMIT 5
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>