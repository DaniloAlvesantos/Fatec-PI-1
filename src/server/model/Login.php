<?php

require_once __DIR__ . "/Database.php";

class Login
{
    public Database $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->db->connect_to();
    }

    public function execLogin($email, $senha)
    {
        if (empty($email) || empty($senha)) {
            return false;
        }

        if (!$this->db->is_connected() || !$this->db->get_PDO()) {
            return false;
        }

        try {
            $query = "SELECT * FROM tb_docente WHERE email = :email";
            $stmt = $this->db->get_PDO()->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $senha == $user['senha']) {
                return $user;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }

    public function execRegister(
        string $nome,
        string $RG,
        string $email,
        int $matricula,
        string $turno,
        string $senha,
        string $cargo,
        bool $outras_fatecs,
        string $curso
    ) {
        if (
            empty($nome) || empty($RG) || empty($email) ||
            empty($matricula) || empty($turno) ||
            empty($senha) || empty($cargo) || empty($curso)
        ) {
            return false;
        }

        if (!$this->db->is_connected() || !$this->db->get_PDO()) {
            return false;
        }

        $query = "INSERT INTO tb_docente 
                    (nome, RG, email, matricula, turno, senha, cargo, outras_fatecs, curso) 
                  VALUES 
                    (:nome, :RG, :email, :matricula, :turno, :senha, :cargo, :outras_fatecs, :curso)";

        try {
            $stmt = $this->db->get_PDO()->prepare($query);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':RG', $RG);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':matricula', $matricula, PDO::PARAM_INT);
            $stmt->bindParam(':turno', $turno);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':cargo', $cargo);
            $stmt->bindValue(':outras_fatecs', $outras_fatecs ? 1 : 0, PDO::PARAM_INT);
            $stmt->bindParam(':curso', $curso);

            return $stmt->execute();
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao registrar: ' . $e->getMessage()
            ]);
            return false;
        }
    }
}
