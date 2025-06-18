<?php

include __DIR__ . "/../controller/configDB.php";

class Database
{
    private string $host = 'localhost';
    private string $db   = 'pi_fatec';
    private string $user = 'root';
    private string $pass = 'root';
    private string $charset = 'utf8mb4';
    private PDO | null $pdo = null;

    public function connect_to()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "❌ Connection failed: " . $e->getMessage();
        }
    }

    public function desconect_to()
    {
        try {
            if ($this->is_connected()) {
                $this->pdo = null;
            }
        } catch (PDOException $err) {
            echo "Erro com conexão";
        }
    }

    public function is_connected()
    {
        return isset($this->pdo);
    }

    public function execAddTables()
    {
        $tablesSQL = returnSQLTables();
        if ($this->is_connected() && !empty($tablesSQL)) {
            try {
                $this->pdo->exec($tablesSQL);
                echo "✅ Tables created successfully!";
            } catch (PDOException $e) {
                echo "❌ Error creating tables: " . $e->getMessage();
            }
        } else {
            echo "❌ No database connection or empty SQL.";
        }
    }

    public function get_PDO(): PDO | null
    {
        return $this->is_connected() ? $this->pdo : null;
    }
}
