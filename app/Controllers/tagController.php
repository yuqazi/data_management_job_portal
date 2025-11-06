<?php
header('Content-Type: application/json');
require_once 'TagModel.php';

$model = new TagModel();
$tags = $model->getTags();

// Return JSON
echo json_encode($tags);
?>
