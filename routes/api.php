<?php
/**
 * API Routes
 */

// Health check
$router->get('/api/health', function () {
    \Core\Response::success(['status' => 'ok'], 'API is running');
});

// Example route
$router->get('/api/example', function () {
    \Core\Response::success(['message' => 'Hello from API']);
});

$router->post('/api/example', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    \Core\Response::created($data, 'Example created');
});
?>
