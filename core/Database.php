<?php
namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function connect(): PDO
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        try {
            $host = Config::get('DB_HOST', 'localhost');
            $port = Config::get('DB_PORT', 3306);
            $database = Config::get('DB_NAME');
            $username = Config::get('DB_USER', 'root');
            $password = Config::get('DB_PASSWORD', '');

            $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
            
            self::$connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            return self::$connection;
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $pdo = self::connect();
        $statement = $pdo->prepare($sql);
        $statement->execute($params);
        return $statement;
    }

    public static function fetch(string $sql, array $params = [])
    {
        return self::query($sql, $params)->fetch();
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function lastInsertId(): string
    {
        return self::connect()->lastInsertId();
    }
}
?>
