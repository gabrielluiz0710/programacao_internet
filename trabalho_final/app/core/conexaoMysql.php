<?php

class Database
{
    private static $host = "sql207.infinityfree.com";
    private static $dbName = "if0_39210114_trabalho_final";
    private static $username = "if0_39210114";
    private static $password = "iUxosPRHCqxw5";
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
                // Em um ambiente real, seria melhor logar o erro do que exibi-lo.
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Falha na conexão com o banco de dados.']);
                exit();
            }
        }
        return self::$pdo;
    }
}