<?php

include_once __DIR__ . "/Database.php";

class Docente
{
    private int $id_docente;
    public string $nome;
    public string $RG;
    public string $email;
    private int $matricula;
    public string $turno;
    private string $senha;
    public string $cargo;
    public bool $outras_fatecs;
    public string $curso;
    public Database $db;

    public function __construct($id_docente = null, $nome = '', $RG = '', $email = '', $matricula = 0, $turno = '', $senha = '', $cargo = '', $outras_fatecs = false, $curso = '')
    {
        $this->db = new Database();
        $this->db->connect_to();

        if ($id_docente !== null) {
            $this->id_docente = $id_docente;
            $this->nome = $nome;
            $this->RG = $RG;
            $this->email = $email;
            $this->matricula = $matricula;
            $this->turno = $turno;
            $this->senha = $senha;
            $this->cargo = $cargo;
            $this->outras_fatecs = $outras_fatecs;
            $this->curso = $curso;
        }
    }

    public static function fromArray(array $data): Docente
    {
        $docente = new self();

        foreach ($data as $key => $value) {
            if (property_exists($docente, $key)) {
                $docente->$key = $value;
            }
        }

        return $docente;
    }

    public function login($email, $senha)
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

    public function getIdDocente(): int
    {
        return $this->id_docente;
    }

    public function getMatricula(): int
    {
        return $this->matricula;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function getDocenteById(int $id_docente): ?Docente
    {
        $query = "SELECT * FROM tb_docente WHERE id_docente = :id_docente";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_docente', $id_docente);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $docente = new self(
                $result['id_docente'],
                $result['nome'],
                $result['RG'],
                $result['email'],
                $result['matricula'],
                $result['turno'],
                $result['senha'],
                $result['cargo'],
                $result['outras_fatecs'],
                $result['curso']
            );
            return $docente;
        }
        return null;
    }

    public function editDocentInfo(int $id_docente, string $query)
    {
        if (empty($query)) {
            echo json_encode(["sucess" => false, "message" => "Query is empty"]);
        }

        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_docente', $id_docente);
        $stmt->execute();
    }
}
