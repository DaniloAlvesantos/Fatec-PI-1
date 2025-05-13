<?php

include "./Database.php";

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

    public function __construct()
    {
        $this->db = new Database();
        $this->db->connect_to();
    }

    public function setDocenteInfo(int $id_docente, string $nome, string $RG, string $email, int $matricula, string $turno, string $senha, string $cargo, bool $outras_fatecs, string $curso)
    {
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
        $query = "SELECT * FROM docentes WHERE id_docente = :id_docente";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_docente', $id_docente);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new Docente()->setDocenteInfo(
                $result['id_docente'],
                $result['nome'],
                $result['RG'],
                $result['email'],
                $result['matricula'],
                $result['turno'],
                $result['senha'],
                $result['cargo'],
                (bool)$result['outras_fatecs'],
                $result['curso']
            );
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
