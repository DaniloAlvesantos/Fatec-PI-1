<?php 

include_once __DIR__ . "/Database.php";

class BancoDeHoras {
    private int $id_bdhrs;
    public int $dia;
    public string $turno;
    public string $horas;
    private int $id_inscricao;
    public Database $db;

    public function __construct() {
        $this->db = new Database();
        $this->db->connect_to();
    }
}