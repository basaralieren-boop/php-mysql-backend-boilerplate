<?php
namespace Core;

class Response
{
    public static function json($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public static function success($data = null, string $message = 'Success', int $statusCode = 200): void
    {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public static function error(string $message, int $statusCode = 400, $data = null): void
    {
        self::json([
            'success' => false,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public static function created($data = null, string $message = 'Created successfully'): void
    {
        self::success($data, $message, 201);
    }

    public static function notFound(string $message = 'Resource not found'): void
    {
        self::error($message, 404);
    }

    public static function unauthorized(string $message = 'Unauthorized'): void
    {
        self::error($message, 401);
    }

    public static function forbidden(string $message = 'Forbidden'): void
    {
        self::error($message, 403);
    }

    public static function validation($errors): void
    {
        self::error('Validation failed', 422, $errors);
    }
}
?>
