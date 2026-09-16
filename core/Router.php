<?php
namespace Core;

class Router
{
    private string $method;
    private string $uri;
    private array $routes = [];

    public function __construct(string $method, string $uri)
    {
        $this->method = $method;
        $this->uri = parse_url($uri, PHP_URL_PATH);
    }

    public function post(string $path, callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    public function patch(string $path, callable $handler): void
    {
        $this->addRoute('PATCH', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch(): void
    {
        foreach ($this->routes as $route) {
            if ($this->matchRoute($route)) {
                call_user_func($route['handler']);
                return;
            }
        }

        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Route not found'
        ]);
    }

    private function matchRoute(array $route): bool
    {
        if ($route['method'] !== $this->method) {
            return false;
        }

        $pattern = $this->convertPathToPattern($route['path']);
        return preg_match($pattern, $this->uri);
    }

    private function convertPathToPattern(string $path): string
    {
        $pattern = str_replace('/', '\/', $path);
        $pattern = preg_replace('/\{[a-zA-Z_][a-zA-Z0-9_]*\}/', '([a-zA-Z0-9_-]+)', $pattern);
        return "/^" . $pattern . "$/";
    }
}
?>
