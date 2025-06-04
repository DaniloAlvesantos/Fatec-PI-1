<?php

include_once __DIR__ . "/Database.php";

class Projeto
{
    private $id_projeto;
    public string $titulo;
    public string $data_inicio;
    public string $data_final;
    public string $id_hae;
    public string $descricoes;
    public string $dias_exec;
    public Database $db;

    public function __construct($id_projeto = null, $titulo = '', $data_inicio = '', $data_final = '', $id_hae = '', $descricoes = null, $dias_exec = null)
    {
        $this->db = new Database();
        $this->db->connect_to();

        if ($id_projeto !== null) {
            $this->id_projeto = $id_projeto;
            $this->titulo = $titulo;
            $this->data_inicio = $data_inicio;
            $this->data_final = $data_final;
            $this->id_hae = $id_hae;
            $this->descricoes = $descricoes;
            $this->dias_exec = $dias_exec;
        }
    }

    public function createProjeto($titulo, $data_inicio, $data_final, $id_hae, $descricao, $dias_exec)
    {
        try {
            $query = "INSERT INTO tb_projeto (titulo, data_inicio, data_final, id_hae, descricoes, dias_exec) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->get_PDO()->prepare($query);
            $stmt->bindParam(1, $titulo);
            $stmt->bindParam(2, $data_inicio);
            $stmt->bindParam(3, $data_final);
            $stmt->bindParam(4, $id_hae);
            $stmt->bindParam(5, $descricao);
            $stmt->bindParam(6, $dias_exec);

            if ($stmt->execute()) {
                $this->id_projeto = $this->db->get_PDO()->lastInsertId();
                return $this->id_projeto;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error in createProjeto: " . $e->getMessage());
            return false;
        }
    }

    public function getProjetoById($id_projeto)
    {
        try {
            $query = "SELECT * FROM tb_projeto WHERE id_projeto = ?";
            $stmt = $this->db->get_PDO()->prepare($query);
            $stmt->bindParam(1, $id_projeto);
            $stmt->execute();
            $results = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($results) {
                return new self(
                    $results['id_projeto'],
                    $results['titulo'],
                    $results['data_inicio'],
                    $results['data_final'],
                    $results['id_hae'],
                    $results['descricoes'],
                    $results['dias_exec']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error in getProjetoById: " . $e->getMessage());
            return false;
        }
    }

    public function getIdProjeto()
    {
        return $this->id_projeto;
    }
}
