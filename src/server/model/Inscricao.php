<?php

include_once __DIR__ . "/Database.php";

class Inscricao
{
    private int $id_inscricao;
    private int $id_docente;
    private int $id_hae;
    private int $id_projeto;
    public string $data_envio;
    public int $quant_hae;
    public int $outras_fatecs;
    public string $status;
    public Database $db;

    public function __construct($id_inscricao = null, $id_docente = null, $id_hae = null, $id_projeto = null, $data_envio = '', $quant_hae = 0, $outras_fatecs = 0, $status = 'Pendente')
    {
        $this->db = new Database();
        $this->db->connect_to();

        if ($id_inscricao !== null) {
            $this->id_inscricao = $id_inscricao;
            $this->id_docente = $id_docente;
            $this->id_hae = $id_hae;
            $this->id_projeto = $id_projeto;
            $this->data_envio = $data_envio;
            $this->quant_hae = $quant_hae;
            $this->outras_fatecs = $outras_fatecs;
            $this->status = $status;
        } else {
            $this->status = $status;
        }
    }

    public function createSubscription($id_docente, $id_hae, $id_projeto, $data_envio, $quant_hae, $outras_fatecs)
    {
        try {
            $query = "INSERT INTO tb_inscricao (id_docente, id_hae, id_projeto, data_envio, quant_hae, outras_fatecs)
                      VALUES (:id_docente, :id_hae, :id_projeto, :data_envio, :quant_hae, :outras_fatecs)";
            $stmt = $this->db->get_PDO()->prepare($query);
            $stmt->bindParam(':id_docente', $id_docente);
            $stmt->bindParam(':id_hae', $id_hae);
            $stmt->bindParam(':id_projeto', $id_projeto);
            $stmt->bindParam(':data_envio', $data_envio);
            $stmt->bindParam(':quant_hae', $quant_hae);
            $stmt->bindParam(':outras_fatecs', $outras_fatecs);
            $stmt->execute();
            $this->id_inscricao = $this->db->get_PDO()->lastInsertId();
            return $this->id_inscricao;
        } catch (PDOException $e) {
            error_log("Error in createSubscription: " . $e->getMessage());
            return false;
        }
    }

    public function getIdInscricao()
    {
        return $this->id_inscricao;
    }

    public function setIdProjeto($id_projeto)
    {
        $this->id_projeto = $id_projeto;
    }

    public function getMySubscriptions($id_docente)
    {
        $query = "SELECT i.id_inscricao, i.id_docente, i.id_hae, i.id_projeto, i.data_envio, i.quant_hae, i.outras_fatecs, p.titulo, i.status
                  FROM tb_inscricao i
                  JOIN tb_projeto p ON i.id_projeto = p.id_projeto
                  WHERE i.id_docente = :id_docente";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_docente', $id_docente);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            return $result;
        } else {
            return [];
        }
    }

    public function getMySubscriptionsById($id_inscricao)
    {
        $query = "SELECT i.*, p.*, h.* FROM tb_inscricao i JOIN tb_projeto p ON i.id_projeto = p.id_projeto JOIN tb_hae h ON i.id_hae = h.id_hae WHERE i.id_inscricao = :id_inscricao";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_inscricao', $id_inscricao);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return new self(
                $result['id_inscricao'],
                $result['id_docente'],
                $result['id_hae'],
                $result['id_projeto'],
                $result['data_envio'],
                $result['quant_hae'],
                $result['outras_fatecs'],
                $result['status'] ?? 'Pendente'
            );
        } else {
            return null;
        }
    }

    public function countSubscriptions($id_hae)
    {
        $query = "SELECT COUNT(*) as count FROM tb_inscricao WHERE id_hae = :id_hae";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_hae', $id_hae);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function getMySubscriptionsByHae($id_hae)
    {
        $query = "SELECT i.*, d.*, h.* FROM tb_inscricao i JOIN tb_docente d ON i.id_docente = d.id_docente JOIN tb_hae h ON i.id_hae = h.id_hae WHERE i.id_hae = :id_hae";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_hae', $id_hae);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            return $result;
        } else {
            return [];
        }
    }

    public function getIdDocente()
    {
        return $this->id_docente;
    }
    public function getIdHae()
    {
        return $this->id_hae;
    }
    public function getIdProjeto()
    {
        return $this->id_projeto;
    }

    public function adminGetAllSubscriptions($cargo, $curso)
    {
        if ($cargo === "Professor") {
            return [];
        }

        if ($cargo === "Coordenador") {
            $query = "SELECT i.*, h.* FROM tb_inscricao i JOIN tb_hae h ON i.id_hae = h.id_hae WHERE h.tip_hae = :curso";
            $stmt = $this->db->get_PDO()->prepare($query);
            $stmt->bindParam(':curso', $curso);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else {
                return [];
            }
        }

        $query = "SELECT i.*, d.*, h.* FROM tb_inscricao i JOIN tb_docente d ON i.id_docente = d.id_docente JOIN tb_hae h ON i.id_hae = h.id_hae";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            return $result;
        } else {
            return [];
        }
    }

    public function getMySubscriptionsByTipHae($tip_hae)
    {
        $query = "SELECT i.*, d.*, h.* FROM tb_inscricao i JOIN tb_docente d ON i.id_docente = d.id_docente JOIN tb_hae h ON i.id_hae = h.id_hae WHERE h.tip_hae = :tip_hae";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':tip_hae', $tip_hae);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            return $result;
        } else {
            return [];
        }
    }

    public function setStatus($status)
    {
        $this->status = $status;
        $query = "UPDATE tb_inscricao SET status = :status WHERE id_inscricao = :id_inscricao";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id_inscricao', $this->id_inscricao);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
