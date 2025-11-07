<?php
header('Content-Type: application/json');
// Require model using correct relative path from this controller
require_once __DIR__ . '/../Models/tagModel.php';

$model = new TagModel();
$tags = $model->getTags();

// Return JSON
echo json_encode($tags);
?>
