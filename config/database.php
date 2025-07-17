<?php
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $host = $_ENV['PGHOST'] ?? 'localhost';
        $port = $_ENV['PGPORT'] ?? '5432';
        $dbname = $_ENV['PGDATABASE'] ?? 'main';
        $username = $_ENV['PGUSER'] ?? 'postgres';
        $password = $_ENV['PGPASSWORD'] ?? '';
        
        try {
            $this->connection = new PDO(
                "pgsql:host={$host};port={$port};dbname={$dbname}",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}

function getDB() {
    return Database::getInstance()->getConnection();
}
?>