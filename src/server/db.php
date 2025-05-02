<?php
require("./configDB.php");

class Database {
    private $host = 'localhost';
    private $db   = 'PI-fatec';  
    private $user = 'root';     
    private $pass = 'root';     
    private $charset = 'utf8mb4';
    private $pdo;
    private $tablesSQL;

    public function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->tablesSQL = returnSQLTables();
            echo "✅ Database connected successfully!<br>";
        } catch (PDOException $e) {
            die("❌ Connection failed: " . $e->getMessage());
        }

        $this->execAddTables();
    }

    public function isConnected() {
        return isset($this->pdo);
    }

    public function execAddTables() {
        if ($this->isConnected() && !empty($this->tablesSQL)) {
            try {
                $this->pdo->exec($this->tablesSQL);
                echo "✅ Tables created successfully!";
            } catch (PDOException $e) {
                echo "❌ Error creating tables: " . $e->getMessage();
            }
        } else {
            echo "❌ No database connection or empty SQL.";
        }
    }
}

new Database();
?>
