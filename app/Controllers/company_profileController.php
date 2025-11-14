<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../Models/companyProfileModel.php';

$companyId = isset($_POST['companyId']) ? intval($_POST['companyId']) : 1;

$company = company_profileModel::getCompany($companyId);

if ($company) {
    echo json_encode($company);
} else {
    echo json_encode(['error' => 'Company not found']);
}
?>
