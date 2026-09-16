<?php
/**
 * Main entry point for the application
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', getenv('APP_DEBUG') ? 1 : 0);

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Load environment variables
require_once BASE_PATH . '/core/Config.php';
require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/core/Database.php';

// Initialize application
try {
    // Initialize router
    $router = new \Core\Router($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    
    // Load routes
    require_once BASE_PATH . '/routes/api.php';
    
    // Dispatch request
    $router->dispatch();
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Internal Server Error',
        'error' => getenv('APP_DEBUG') ? $e->getMessage() : null
    ]);
}
?>
