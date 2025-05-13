<?php

include_once __DIR__ . "/Database.php";

class Relatorio {
    private int $id_relatorio;
    private int $id_projeto;
    public string $data_entrega;
    public string $pdf_url;
    private int $id_feedback;
    public Database $db;

    public function __construct() {
        $this->db = new Database();
        $this->db->connect_to();
    }
    
}