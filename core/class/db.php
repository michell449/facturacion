<?php
// core/class/db.php
require_once dirname(__DIR__, 2) . '/config.php';

class Database {

    private $host = DB_HOST;
    private $database_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASSWORD;

    public $conn;
    public $tables;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->database_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
                ]
            );
            
            // Asegurar UTF-8 en todas las consultas
            $this->conn->exec("SET CHARACTER SET utf8mb4");
            $this->conn->exec("SET character_set_connection=utf8mb4");
            $this->conn->exec("SET character_set_client=utf8mb4");
            $this->conn->exec("SET character_set_results=utf8mb4");

            // cargar tablas
            $sqlQuery = "SHOW TABLES FROM  {$this->database_name}";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();

            $record = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $record[] = $row;
            }

            $this->tables = $record;
            
        } catch (PDOException $exception) {
            echo "Database could not be connected: " . $exception->getMessage();
        }

        return $this->conn;
    }

    public function chktable($tabla) {
        $result = false;
        if (!empty($this->tables)) {
            foreach ($this->tables as $valor) {
                foreach ($valor as $field) {
                    if ($tabla == $field) return true;
                }
            }
        }
        return false;
    }
}
?>
