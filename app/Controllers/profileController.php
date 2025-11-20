<?php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../Models/profileModel.php';

// -------------------------------
// Determine which user ID to load
// -------------------------------

// If logged in → ALWAYS use session user
if (isset($_SESSION['user']) && isset($_SESSION['user']['userID'])) {
    $userId = intval($_SESSION['user']['userID']);
}
// If not logged in → allow manual ?id=123
else {
    if (isset($_GET['id'])) {
        $userId = intval($_GET['id']);
    } else {
        echo json_encode(["error" => "No user session and no ?id provided"]);
        exit;
    }
}

// -------------------------------
// GET request → return profile info
// -------------------------------
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

    exit;
}

// -------------------------------
// POST request → update profile
// -------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $action = $data['action'] ?? null;

        if (!$action) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing action']);
            exit;
        }

        $success = false;
        $message = '';

        switch ($action) {

            case 'updateAbout':
                $about = $data['about'] ?? '';
                $success = UserModel::updateAbout($userId, $about);
                $message = $success ? 'About updated successfully' : 'Failed to update about';
                break;

            case 'addExperience':
                $title = trim($data['title'] ?? '');
                $duration = trim($data['duration'] ?? '');
                $success = UserModel::addWorkExperience($userId, $title, $duration);
                $message = $success ? 'Work experience added' : 'Failed to add work experience';
                break;

            case 'addCertification':
                $certificate = trim($data['certificate'] ?? '');
                $success = UserModel::addCertification($userId, $certificate);
                $message = $success ? 'Certification added' : 'Failed to add certification';
                break;

            case 'addSkill':
                $skill = intval($data['skill'] ?? 0);
                $success = UserModel::addSkill($userId, $skill);
                $message = $success ? 'Skill added' : 'Failed to add skill';
                break;

            default:
                http_response_code(400);
                echo json_encode(['error' => 'Invalid action']);
                exit;
        }

        echo json_encode(['success' => $success, 'message' => $message]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }

    exit;
}

// -------------------------------
// Unsupported HTTP Method
// -------------------------------
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
