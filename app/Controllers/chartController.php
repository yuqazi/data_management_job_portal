<?php

header('Content-Type: application/json');
// Require model using correct relative path from this controller
require_once __DIR__ . '/../Models/chartModel.php';

$topSkills = getSkillsGraph();

echo json_encode($topSkills, JSON_PRETTY_PRINT);

?>