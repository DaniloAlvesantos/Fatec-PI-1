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

    public function add($table, $data) {
        $table = returnTable(TablesEnum::$table);
        $formated_data = implode(", ", $data);
        $query = "INSERT INTO $table VALUES ($formated_data)";
        $search_query = "SELECT id_docente, nome FROM $table WHERE email = $data['email']"

        if($this->isConnected()) {
            try {
                $search = $this->pdo->exec($search_query);
                if($search) {
                   return $search; 
                }

                return $this->pdo->exec($query);
            } catch (PDOException $e) {
                echo "❌ Error executing query: " . $e->getMessage();
            }
        }

    }
}
?>
