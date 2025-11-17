<?php

// Base paths
define('BASE_PATH', __DIR__ . '/');
define('VIEWS_PATH', BASE_PATH . 'resources/views/');
define('CONTROLLERS_PATH', BASE_PATH . 'app/Controllers/');

// ----------------------------------------
// Normalize the request path
// ----------------------------------------
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove script name (/index.php) IF present
$request_uri = str_replace('/index.php', '', $request_uri);

// Detect base folder dynamically
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$base_folder = ($script_name === '/') ? '' : $script_name;
if (strpos($request_uri, $base_folder) === 0) {
    $request_uri = substr($request_uri, strlen($base_folder));
}

// Clean up to "index", "login", "api/tags", etc
$path = trim($request_uri, '/');
$path = strtolower($path);

// Default route
if ($path === '' || $path === '/') {
    $path = 'index';
}

// ----------------------------------------
// Route definitions
// ----------------------------------------
$routes = [

    // HTML Views
    'index' => VIEWS_PATH . 'index.html',
    'search' => VIEWS_PATH . 'index.html',
    'profile' => VIEWS_PATH . 'profile.html',
    'sign-in' => VIEWS_PATH . 'sign-in.html',
    'create-account' => VIEWS_PATH . 'Create-account.html',
    'org-create-account' => VIEWS_PATH . 'orgCreate-account.html',
    'job-details' => VIEWS_PATH . 'job-details.html',
    'apply' => VIEWS_PATH . 'apply.html',
    'applications' => VIEWS_PATH . 'applications.html',
    'create-job' => VIEWS_PATH . 'create_job.html',
    'company-profile' => VIEWS_PATH . 'company_profile.html',

    // API Controllers
    'api/profile' => CONTROLLERS_PATH . 'profileController.php',
    'api/tags' => CONTROLLERS_PATH . 'tagController.php',
    'api/create-account' => CONTROLLERS_PATH . 'Create-accountController.php',
    'api/org-create-account' => CONTROLLERS_PATH . 'orgCreate-accountController.php',
    'api/sign-in' => CONTROLLERS_PATH . 'sign-inController.php',
    'api/index' => CONTROLLERS_PATH . 'indexController.php',
];

// ----------------------------------------
// Serve HTML files
// ----------------------------------------
function serveHtml($filePath) {
    if (!file_exists($filePath)) {
        http_response_code(404);
        echo "404 - View not found";
        exit;
    }
    header("Content-Type: text/html; charset=utf-8");
    readfile($filePath);
    exit;
}

// ----------------------------------------
// Serve controller files
// ----------------------------------------
function serveController($filePath) {
    if (!file_exists($filePath)) {
        http_response_code(404);
        echo json_encode(["error" => "Controller not found"]);
        exit;
    }
    header("Content-Type: application/json; charset=utf-8");
    require $filePath;
    exit;
}

// ----------------------------------------
// Handle routes
// ----------------------------------------
if (array_key_exists($path, $routes)) {

    $target = $routes[$path];

    if (str_ends_with($target, '.html')) {
        serveHtml($target);
    } else {
        serveController($target);
    }
}

// ----------------------------------------
// Static files (CSS/JS/images)
// ----------------------------------------
$static_file = BASE_PATH . $path;
if (file_exists($static_file) && is_file($static_file)) {
    readfile($static_file);
    exit;
}

// ----------------------------------------
// 404
// ----------------------------------------
http_response_code(404);
echo "404 - Route not found: " . htmlspecialchars($path);
exit;
?>
