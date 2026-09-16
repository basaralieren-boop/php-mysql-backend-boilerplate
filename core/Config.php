<?php
namespace Core;

class Config
{
    private static array $config = [];

    public static function load(): void
    {
        $envFile = dirname(__DIR__) . '/.env';
        
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '#') === 0) continue;
                if (strpos($line, '=') === false) continue;
                
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                putenv("$key=$value");
                self::$config[$key] = $value;
            }
        }
    }

    public static function get(string $key, $default = null)
    {
        self::load();
        return getenv($key) ?: $default;
    }

    public static function all(): array
    {
        self::load();
        return self::$config;
    }
}

Config::load();
?>
