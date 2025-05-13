<?php

include_once __DIR__ . "/Database.php";

class Feedback {
    private int $id_feedback;
    private int $id_inscricao;
    public string $data_envio;
    public string $descricao;
    private int $id_coor;
    private int $id_diretor;
    public string $observacao;
    public string $resultado;
    public Database $db;

    public function __construct() {
        $this->db = new Database();
        $this->db->connect_to();
    }

    
}