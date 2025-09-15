<?php

class Database
{
    private static $host = "sql105.infinityfree.com";
    private static $dbName = "if0_39936798_autofacil";
    private static $username = "if0_39936798";
    private static $password = "XudiANQj26";
    private static $pdo;

    public static function connect()
    {
        if (!isset(self::$pdo)) {
            try {
                $options = [
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ];
                self::$pdo = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbName . ";charset=utf8mb4", self::$username, self::$password, $options);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Falha na conexão com o banco de dados.']);
                exit();
            }
        }
        return self::$pdo;
    }
}