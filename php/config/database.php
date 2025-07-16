<?php
/**
 * Database configuration for PostgreSQL
 */

class Database {
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;
    private $pdo;

    public function __construct() {
        // Parse DATABASE_URL or use individual environment variables
        $database_url = getenv('DATABASE_URL');
        
        if ($database_url) {
            $url = parse_url($database_url);
            $this->host = $url['host'];
            $this->port = $url['port'] ?? 5432;
            $this->dbname = ltrim($url['path'], '/');
            $this->username = $url['user'];
            $this->password = $url['pass'];
        } else {
            $this->host = getenv('PGHOST') ?: 'localhost';
            $this->port = getenv('PGPORT') ?: 5432;
            $this->dbname = getenv('PGDATABASE') ?: 'transport_registry';
            $this->username = getenv('PGUSER') ?: 'postgres';
            $this->password = getenv('PGPASSWORD') ?: '';
        }
    }

    public function connect() {
        if ($this->pdo === null) {
            try {
                $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
                $this->pdo = new PDO($dsn, $this->username, $this->password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
            } catch (PDOException $e) {
                throw new Exception("Database connection failed: " . $e->getMessage());
            }
        }
        return $this->pdo;
    }

    public function getPdo() {
        return $this->connect();
    }
}