<?php

include_once __DIR__ . "/Database.php";

class Chamada
{
    private int $id_chamada;
    private int $id_hae;
    private int $id_inscricao;
    public string $data_envio;
    public int $quant_hae;
    public string $status;
    public string $justificativa;
    private Database $db;

    public function __construct($id_chamada = null, $id_hae = null, $id_inscricao = null, $data_envio = "", $quant_hae = null, $status = "", $justificativa = "")
    {
        $this->db = new Database();
        $this->db->connect_to();

        if ($id_chamada !== null) {
            $this->id_chamada = $id_chamada;
            $this->id_hae = $id_hae;
            $this->id_inscricao = $id_inscricao;
            $this->data_envio = $data_envio;
            $this->quant_hae = $quant_hae;
            $this->status = $status;
            $this->justificativa = $justificativa;
        }
    }

    public function getIdChamada(): int
    {
        return $this->id_chamada;
    }

    public function getIdHAE(): int
    {
        return $this->id_hae;
    }

    public function getIdInscricao(): int
    {
        return $this->id_inscricao;
    }

    public function getDataEnvio(): string
    {
        return $this->data_envio;
    }

    public function getChamadaForm($id_hae)
    {
        $query = "SELECT i.id_inscricao, i.data_envio as dataEnvioInscricao, i.quant_hae as quantHAEInscricao, 
                  i.status as statusInscricao,
                  d.nome as nomeDocente
                  FROM tb_inscricao i
                  JOIN tb_docente d ON i.id_docente = d.id_docente
                  JOIN tb_hae h ON i.id_hae = h.id_hae
                  WHERE i.status = 'Aprovada' AND i.id_hae = :id_hae";

        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_hae', $id_hae);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

    public function getPendenteChamadas()
    {
        $query = "SELECT h.* 
                  FROM tb_hae h
                  WHERE NOW() > h.data_final";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

    public function processarResultadoChamada($id_inscricao, $quant_hae, $status, $justificativa, $num_chamada, $semestre)
    {
        try {
            $this->db->get_PDO()->beginTransaction();

            $checkQuery = "SELECT COUNT(*) FROM tb_chamada c 
                          JOIN tb_inscricao i ON c.id_inscricao = i.id_inscricao 
                          WHERE c.id_inscricao = :id_inscricao AND c.id_hae = (SELECT id_hae FROM tb_inscricao WHERE id_inscricao = :id_inscricao2)";

            $checkStmt = $this->db->get_PDO()->prepare($checkQuery);
            $checkStmt->bindParam(':id_inscricao', $id_inscricao);
            $checkStmt->bindParam(':id_inscricao2', $id_inscricao);
            $checkStmt->execute();
            $exists = $checkStmt->fetchColumn() > 0;

            if ($exists) {
                $updateQuery = "UPDATE tb_chamada SET 
                               quant_hae = :quant_hae, 
                               status = :status, 
                               justificativa = :justificativa, 
                               num_chamada = :num_chamada, 
                               semestre = :semestre,
                               data_envio = NOW()
                               WHERE id_inscricao = :id_inscricao";

                $stmtUpdate = $this->db->get_PDO()->prepare($updateQuery);
                $stmtUpdate->bindParam(':id_inscricao', $id_inscricao);
                $stmtUpdate->bindParam(':quant_hae', $quant_hae);
                $stmtUpdate->bindParam(':status', $status);
                $stmtUpdate->bindParam(':justificativa', $justificativa);
                $stmtUpdate->bindParam(':num_chamada', $num_chamada);
                $stmtUpdate->bindParam(':semestre', $semestre);
                $stmtUpdate->execute();
            } else {
                $queryChamada = "INSERT INTO tb_chamada (id_hae, id_inscricao, data_envio, quant_hae, status, justificativa, num_chamada, semestre) 
                                VALUES ((SELECT id_hae FROM tb_inscricao WHERE id_inscricao = :id_inscricao), :id_inscricao, NOW(), :quant_hae, :status, :justificativa, :num_chamada, :semestre)";

                $stmtChamada = $this->db->get_PDO()->prepare($queryChamada);
                $stmtChamada->bindParam(':id_inscricao', $id_inscricao);
                $stmtChamada->bindParam(':quant_hae', $quant_hae);
                $stmtChamada->bindParam(':status', $status);
                $stmtChamada->bindParam(':justificativa', $justificativa);
                $stmtChamada->bindParam(':num_chamada', $num_chamada);
                $stmtChamada->bindParam(':semestre', $semestre);
                $stmtChamada->execute();
            }

            $novoStatusInscricao = ($status === 'Deferido') ? 'Aprovada' : 'Reprovada';

            $queryUpdateInscricao = "UPDATE tb_inscricao SET status = :novo_status WHERE id_inscricao = :id_inscricao";
            $stmtUpdate = $this->db->get_PDO()->prepare($queryUpdateInscricao);
            $stmtUpdate->bindParam(':novo_status', $novoStatusInscricao);
            $stmtUpdate->bindParam(':id_inscricao', $id_inscricao);
            $stmtUpdate->execute();

            $this->db->get_PDO()->commit();
            return true;
        } catch (Exception $e) {
            $this->db->get_PDO()->rollback();
            error_log("Erro ao processar chamada: " . $e->getMessage());
            return false;
        }
    }

    public function getResultadosChamada($id_hae, $num_chamada, $semestre)
    {
        $query = "SELECT c.*, d.nome as nomeDocente, i.quant_hae as quantSolicitada
                  FROM tb_chamada c
                  JOIN tb_inscricao i ON c.id_inscricao = i.id_inscricao
                  JOIN tb_docente d ON i.id_docente = d.id_docente
                  WHERE c.id_hae = :id_hae AND c.num_chamada = :num_chamada AND c.semestre = :semestre
                  ORDER BY c.data_envio DESC";

        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_hae', $id_hae);
        $stmt->bindParam(':num_chamada', $num_chamada);
        $stmt->bindParam(':semestre', $semestre);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

    public function getAllChamadasGrouped() {
        $query = "SELECT 
            c.id_hae,
            c.num_chamada,
            c.semestre,
            MAX(c.data_envio) as data_envio,
            h.titulo as titulo_hae,
            h.tip_hae,
            COUNT(*) as total_inscricoes,
            SUM(CASE WHEN c.status = 'Deferido' THEN 1 ELSE 0 END) as total_deferidos,
            SUM(CASE WHEN c.status = 'Indeferido' THEN 1 ELSE 0 END) as total_indeferidos,
            SUM(CASE WHEN c.status = 'Deferido' THEN c.quant_hae ELSE 0 END) as total_horas_deferidas
          FROM tb_chamada c
          JOIN tb_hae h ON c.id_hae = h.id_hae
          GROUP BY c.id_hae, c.num_chamada, c.semestre, h.titulo, h.tip_hae
          ORDER BY data_envio DESC";
        
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
    
    public function getChamadasByHAE($id_hae) {
        $query = "SELECT 
                    c.*,
                    d.nome as nomeDocente,
                    i.quant_hae as quantSolicitada
                  FROM tb_chamada c
                  JOIN tb_inscricao i ON c.id_inscricao = i.id_inscricao
                  JOIN tb_docente d ON i.id_docente = d.id_docente
                  WHERE c.id_hae = :id_hae
                  ORDER BY c.num_chamada, c.semestre, c.data_envio DESC";
        
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_hae', $id_hae);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
    
    public function getDetalhesResultadoChamada($id_hae, $num_chamada, $semestre) {
        $query = "SELECT 
                    c.*,
                    d.nome as nomeDocente,
                    d.email as emailDocente,
                    i.quant_hae as quantSolicitada,
                    h.titulo as tituloHAE,
                    h.tip_hae as tipoHAE
                  FROM tb_chamada c
                  JOIN tb_inscricao i ON c.id_inscricao = i.id_inscricao
                  JOIN tb_docente d ON i.id_docente = d.id_docente
                  JOIN tb_hae h ON c.id_hae = h.id_hae
                  WHERE c.id_hae = :id_hae 
                    AND c.num_chamada = :num_chamada 
                    AND c.semestre = :semestre
                  ORDER BY d.nome ASC";
        
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_hae', $id_hae);
        $stmt->bindParam(':num_chamada', $num_chamada);
        $stmt->bindParam(':semestre', $semestre);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
}
