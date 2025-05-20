<?php

class Projeto
{
    private int $id_projeto;
    public string $titlulo;
    public string $data_inicio;
    public string $data_final;
    public string $id_hae;
    public string $descricao;
    public Database $db;

    public function __construct($id_projeto = null, $titlulo = '', $data_inicio = '', $data_final = '', $id_hae = '', $descricao = null)
    {
        $this->db = new Database();
        $this->db->connect_to();

        if ($id_projeto !== null) {
            $this->id_projeto = $id_projeto;
            $this->titlulo = $titlulo;
            $this->data_inicio = $data_inicio;
            $this->data_final = $data_final;
            $this->id_hae = $id_hae;
            $this->descricao = $descricao;
        }
    }

    public function createProjeto($titulo, $data_inicio, $data_final, $id_hae, $descricao)
    {
        try {
            $query = "INSERT INTO tb_projeto (titulo, data_inicio, data_final, id_hae, descricoes) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->get_PDO()->prepare($query);
            $stmt->bindParam(1, $titulo);
            $stmt->bindParam(2, $data_inicio);
            $stmt->bindParam(3, $data_final);
            $stmt->bindParam(4, $id_hae);
            $stmt->bindParam(5, $descricao);

            if ($stmt->execute()) {
                return $this->db->get_PDO()->lastInsertId();
            }
        } catch (PDOException $e) {
            error_log("Error in createProjeto: " . $e->getMessage());
            return false;
        }
    }

    public function getIdProjeto()
    {
        return $this->id_projeto;
    }
}
