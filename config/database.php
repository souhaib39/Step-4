<?php
class Database {
    private $host = "localhost";
    private $db_name = "step-4";
    private $username = "root";
    private $password = "";
    private $conn;

    
    public function connect() {
        $this->conn = null;
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage()); 
            return null;
        }
        return $this->conn;
    }

    public function getConnection() {
        if ($this->conn === null) {
            $this->connect();
        }
        return $this->conn;
    }
   
    public function prepare($sql) {
        return $this->getConnection()->prepare($sql);
    }

    public function close() {
        $this->conn = null;
    }
}
?>