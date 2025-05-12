<?php

include "./Database.php";

class Docente {
    private int $id_docente;
    public string $nome;
    public string $RG;
    public string $email;
    private int $matricula;
    public string $turno;
    private string $senha;
    public string $cargo;
    public boolean $outras_fatecs;
    public string $curso;
    private Database $db;

    public function __construct(int $id_docente, string $nome, string $RG, string $email, int $matricula, string $turno, string $senha, string $cargo, boolean $outras_fatecs, string $curso) {
        $this->id_docente = $id_docente;
        $this->nome = $nome;
        $this->RG = $RG;
        $this->email = $email;
        $this->matricula = $matricula;
        $this->turno = $turno;
        $this->senha = password_hash($senha, PASSWORD_DEFAULT);
        $this->cargo = $cargo;
        $this->outras_fatecs = $outras_fatecs;
        $this->curso = $curso;
        $this->db = new Database();
    }
}