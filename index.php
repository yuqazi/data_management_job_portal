<?php
/**
 * Router for Job Portal Application
 * 
 * This file routes all requests to the appropriate views or controllers.
 * Place this at the project root and configure your web server to route all
 * requests to this file (see .htaccess or web server config instructions below).
 * 
 * Usage:
 *   - /index → shows index view
 *   - /search → shows index view (alias)
 *   - /profile → shows profile view
 *   - /sign-in → shows sign-in view
 *   - /create-account → shows create-account view
 *   - /org-create-account → shows org create-account view
 *   - /api/users/{id} → calls profileController
 *   - /api/tags → calls tagController
 *   etc.
 */

// Define base paths
define('BASE_PATH', __DIR__ . '/');
define('VIEWS_PATH', BASE_PATH . 'resources/views/');
define('CONTROLLERS_PATH', BASE_PATH . 'app/Controllers/');

// Get the requested URI and clean it
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base_url = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

// Remove base URL from the request URI if it exists
if ($base_url !== '' && strpos($request_uri, $base_url) === 0) {
    $request_uri = substr($request_uri, strlen($base_url));
}

// Clean the request path
$path = trim($request_uri, '/');
$path = strtolower($path);

// Empty path routes to index
if ($path === '' || $path === 'index') {
    $path = 'index';
}

// Define routes: map request paths to views or controllers
$routes = [
    // Page routes (serve HTML views)
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
    
    // API/Controller routes (JSON responses)
    'api/profile' => CONTROLLERS_PATH . 'profileController.php',
    'api/tags' => CONTROLLERS_PATH . 'tagController.php',
    'api/create-account' => CONTROLLERS_PATH . 'Create-accountController.php',
    'api/org-create-account' => CONTROLLERS_PATH . 'orgCreate-accountController.php',
    'api/sign-in' => CONTROLLERS_PATH . 'sign-inController.php',
    'api/index' => CONTROLLERS_PATH . 'indexController.php',
];

// Function to serve HTML files
function serveHtml($filePath) {
    if (!file_exists($filePath)) {
        header("HTTP/1.0 404 Not Found");
        echo "404 - Page not found";
        exit;
    }
    header('Content-Type: text/html; charset=utf-8');
    readfile($filePath);
    exit;
}

// Function to serve PHP controllers
function serveController($filePath) {
    if (!file_exists($filePath)) {
        header("HTTP/1.0 404 Not Found");
        http_response_code(404);
        echo json_encode(["error" => "Controller not found"]);
        exit;
    }
    // Set content type for API responses
    header('Content-Type: application/json; charset=utf-8');
    require_once $filePath;
    exit;
}

// Route the request
if (isset($routes[$path])) {
    $route_target = $routes[$path];
    
    // Determine if this is a page route (HTML) or API route (PHP)
    if (strpos($route_target, '.html') !== false) {
        serveHtml($route_target);
    } else {
        serveController($route_target);
    }
} else {
    // Try direct file access for static files (CSS, JS, images, etc.)
    $requested_file = BASE_PATH . $path;
    
    if (file_exists($requested_file) && is_file($requested_file)) {
        // Serve static file
        $mime_types = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'json' => 'application/json',
            'xml' => 'text/xml',
        ];
        
        $extension = strtolower(pathinfo($requested_file, PATHINFO_EXTENSION));
        $mime_type = $mime_types[$extension] ?? 'application/octet-stream';
        
        header("Content-Type: {$mime_type}");
        readfile($requested_file);
        exit;
    }
    
    // 404: Route not found
    header("HTTP/1.0 404 Not Found");
    http_response_code(404);
    echo "404 - Route not found: " . htmlspecialchars($path);
    exit;
}
?>
