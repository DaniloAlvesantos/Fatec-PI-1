<?php

include_once __DIR__ . "/Database.php";

class Relatorio
{
    private int $id_relatorio;
    private int $id_projeto;
    public string $data_entrega;
    public string $pdf_url;
    public string $pdf_nome;
    private int $id_feedback;
    public Database $db;

    public function __construct($id_feedback = null, $id_projeto = null, $data_entrega = null, $pdf_url = null, $pdf_nome = null)
    {
        $this->db = new Database();
        $this->db->connect_to();

        if ($id_feedback !== null) {
            $this->id_feedback = $id_feedback;
            $this->id_projeto = $id_projeto;
            $this->data_entrega = $data_entrega;
            $this->pdf_url = $pdf_url;
            $this->pdf_nome = $pdf_nome;
        }
    }


    public function getIdRelatorio()
    {
        return $this->id_relatorio;
    }

    public function getRelatorios($id_docente)
    {
        $approved_inscricoes = "SELECT i.id_inscricao,i.data_envio, h.titulo as haeTitulo, h.tip_hae, p.titulo, p.data_final 
                                FROM tb_inscricao i 
                                JOIN tb_hae h ON i.id_hae = h.id_hae 
                                JOIN tb_projeto p ON i.id_projeto = p.id_projeto  
                                WHERE i.status = 'Aprovada' AND i.id_docente = :id_docente
                                ORDER BY i.data_envio DESC";

        $stmt = $this->db->get_PDO()->prepare($approved_inscricoes);
        $stmt->bindParam(':id_docente', $id_docente);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            return $result;
        } else {
            return [];
        }
    }
}
