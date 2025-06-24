<?php

class Database {
    private static $instance = null;
    private $pdo;
    private $connectionAttempts = 0;
    private const MAX_RETRY_ATTEMPTS = 3;
    private const RETRY_DELAY = 1; // segundos

    private const DB_CONFIG = [
        'host' => '161.132.45.228',
        'dbname' => 'sistema_mentoria',
        'user' => 'ghuanca',
        'pass' => 'Upt2025',
        'charset' => 'utf8mb4',
        'port' => 3306,
        'timeout' => 30,
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_TIMEOUT => 30,
            PDO::ATTR_PERSISTENT => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
            PDO::MYSQL_ATTR_FOUND_ROWS => true,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
        ]
    ];

    private function __construct() {
        $this->connect();
    }

    private function __clone() {}

    public function __wakeup() {
        throw new Exception("No se puede deserializar una instancia de " . __CLASS__);
    }

    private function connect(): void {
        $this->connectionAttempts = 0;
        
        while ($this->connectionAttempts < self::MAX_RETRY_ATTEMPTS) {
            try {
                $this->connectionAttempts++;
                
                $dsn = sprintf(
                    "mysql:host=%s;port=%d;dbname=%s;charset=%s",
                    self::DB_CONFIG['host'],
                    self::DB_CONFIG['port'],
                    self::DB_CONFIG['dbname'],
                    self::DB_CONFIG['charset']
                );

                $this->pdo = new PDO(
                    $dsn,
                    self::DB_CONFIG['user'],
                    self::DB_CONFIG['pass'],
                    self::DB_CONFIG['options']
                );

                $this->pdo->query('SELECT 1');
                
                error_log("✅ Conexión exitosa a BD remota (intento {$this->connectionAttempts})");
                return;

            } catch (PDOException $e) {
                error_log("❌ Error conexión BD (intento {$this->connectionAttempts}): " . $e->getMessage());
                
                if ($this->connectionAttempts >= self::MAX_RETRY_ATTEMPTS) {
                    error_log("🚨 Máximo de intentos alcanzado. Falló conexión a BD.");
                    throw new Exception("Error al conectar con la base de datos después de " . self::MAX_RETRY_ATTEMPTS . " intentos");
                }
                
                sleep(self::RETRY_DELAY);
            }
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        if (!$this->isConnected()) {
            error_log("🔄 Reconectando a la base de datos...");
            $this->connect();
        }
        return $this->pdo;
    }

    private function isConnected(): bool {
        try {
            if ($this->pdo === null) {
                return false;
            }
            
            $stmt = $this->pdo->query('SELECT 1 as ping');
            return $stmt !== false;
            
        } catch (PDOException $e) {
            error_log("⚠️ Conexión perdida: " . $e->getMessage());
            return false;
        }
    }

    public function query(string $sql, array $params = []): PDOStatement {
        $attempts = 0;
        $maxAttempts = 2;
        
        while ($attempts < $maxAttempts) {
            try {
                $stmt = $this->getConnection()->prepare($sql);
                $stmt->execute($params);
                return $stmt;
                
            } catch (PDOException $e) {
                $attempts++;
                
                $retryableCodes = [2006, 2013, 2002, 1045];
                
                if (in_array($e->getCode(), $retryableCodes) && $attempts < $maxAttempts) {
                    error_log("🔄 Reintentando query debido a error de conexión: " . $e->getMessage());
                    $this->pdo = null;
                    sleep(1);
                    continue;
                }
                
                error_log("ERROR MYSQL: " . $e->getMessage());
                error_log("CÓDIGO: " . $e->getCode());
                error_log("SQL: " . $sql);
                error_log("PARAMS: " . print_r($params, true));
                error_log("INTENTO: " . $attempts . "/" . $maxAttempts);

                throw $e;
            }
        }
        
        throw new Exception("Query falló después de $maxAttempts intentos");
    }

    public function fetchOne(string $sql, array $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    public function fetchAll(string $sql, array $params = []): array {
        return $this->query($sql, $params)->fetchAll();
    }

    public function insert(string $sql, array $params = []): string {
        try {
            error_log("🔍 DEBUG INSERT - SQL: " . $sql);
            error_log("🔍 DEBUG INSERT - Params: " . json_encode($params, JSON_UNESCAPED_UNICODE));
            
            $this->query($sql, $params);
            $lastId = $this->getConnection()->lastInsertId();
            
            error_log("🔍 DEBUG INSERT - Last Insert ID: " . $lastId);
            
            return $lastId;
            
        } catch (Exception $e) {
            error_log("🔥 ERROR en insert(): " . $e->getMessage());
            throw $e;
        }
    }

    public function execute(string $sql, array $params = []): int {
        return $this->query($sql, $params)->rowCount();
    }

    public function beginTransaction(): bool {
        try {
            return $this->getConnection()->beginTransaction();
        } catch (PDOException $e) {
            error_log("Error iniciando transacción: " . $e->getMessage());
            throw $e;
        }
    }

    public function commit(): bool {
        try {
            return $this->getConnection()->commit();
        } catch (PDOException $e) {
            error_log("Error en commit: " . $e->getMessage());
            throw $e;
        }
    }

    public function rollback(): bool {
        try {
            return $this->getConnection()->rollback();
        } catch (PDOException $e) {
            error_log("Error en rollback: " . $e->getMessage());
            throw $e;
        }
    }

    public function inTransaction(): bool {
        try {
            return $this->getConnection()->inTransaction();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function close(): void {
        if ($this->pdo !== null) {
            $this->pdo = null;
        }
        self::$instance = null;
        error_log("🔌 Conexión a BD cerrada");
    }

    public function __destruct() {
        $this->close();
    }
}