<?php

include_once __DIR__ . "/Database.php";

class HAE
{
    private int $id_hae;
    public string $titulo;
    public string $descricao;
    public string $data_inicio;
    public string $data_final;
    public int $quant_hae;
    public string $tip_hae;
    public Database $db;

    public function __construct($id_hae = null, $titulo = '', $descricao = '', $data_inicio = '', $data_final = '', $quant_hae = 0, $tip_hae = '')
    {
        $this->db = new Database();
        $this->db->connect_to();

        if ($id_hae !== null) {
            $this->id_hae = $id_hae;
            $this->titulo = $titulo;
            $this->descricao = $descricao;
            $this->data_inicio = $data_inicio;
            $this->data_final = $data_final;
            $this->quant_hae = $quant_hae;
            $this->tip_hae = $tip_hae;
        }
    }

    public function getIdHAE(): int
    {
        return $this->id_hae;
    }

    public function getHaes(): array
    {
        $query = "SELECT * FROM tb_hae LIMIT 10";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHAEById(int $id_hae): ?HAE
    {
        $query = "SELECT * FROM tb_hae WHERE id_hae = :id_hae";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_hae', $id_hae);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new self(
                $result['id_hae'],
                $result['titulo'],
                $result['descricao'],
                $result['data_inicio'],
                $result['data_final'],
                $result['quant_hae'],
                $result['tip_hae']
            );
        }
        return null;
    }

    public function getHAEByTip($tip_hae)
    {
        $query = "SELECT * FROM tb_hae WHERE tip_hae = :tip_hae";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':tip_hae', $tip_hae);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createHAE($titulo, $descricao, $data_inicio, $data_final, $quant_hae, $tip_hae)
    {
        $query = "INSERT INTO tb_hae (titulo, descricao, data_inicio, data_final, quant_hae, tip_hae) VALUES (:titulo, :descricao, :data_inicio, :data_final, :quant_hae, :tip_hae)";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_final', $data_final);
        $stmt->bindParam(':quant_hae', $quant_hae);
        $stmt->bindParam(':tip_hae', $tip_hae);

        return $stmt->execute();
    }
}
