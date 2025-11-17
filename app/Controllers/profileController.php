<?php
header('Content-Type: application/json');
// Require the profile model using a correct relative path from this controller

require_once __DIR__ . '/../Models/profileModel.php';

// Get the user ID from query string or POST data (default to 1)
$userId = 1;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = isset($_GET['id']) ? intval($_GET['id']) : 1;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = isset($data['id']) ? intval($data['id']) : (isset($_GET['id']) ? intval($_GET['id']) : 1);
}

// Handle GET requests - fetch user profile
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $user = UserModel::getUser($userId);
        
        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

// Handle POST requests - update profile data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $action = $data['action'] ?? null;
        $success = false;
        $message = '';

        switch ($action) {
            case 'updateAbout':
                $about = $data['about'] ?? '';
                $success = UserModel::updateAbout($userId, $about);
                $message = $success ? 'About section updated successfully' : 'Failed to update about section';
                break;

            case 'addExperience':
                $title = $data['title'] ?? '';
                $duration = $data['duration'] ?? '';
                $success = UserModel::addWorkExperience($userId, $title, $duration);
                $message = $success ? 'Work experience added successfully' : 'Failed to add work experience';
                break;

            case 'addCertification':
                $certificate = $data['certificate'] ?? '';
                $success = UserModel::addCertification($userId, $certificate);
                $message = $success ? 'Certification added successfully' : 'Failed to add certification';
                break;

            case 'addSkill':
                $skill = $data['skill'] ?? '';
                $success = UserModel::addSkill($userId, $skill);
                $message = $success ? 'Skill added successfully' : 'Failed to add skill';
                break;

            default:
                http_response_code(400);
                echo json_encode(['error' => 'Invalid action']);
                exit;
        }

        http_response_code($success ? 200 : 400);
        echo json_encode(['success' => $success, 'message' => $message]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
