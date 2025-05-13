<?php 

include_once __DIR__ . "/Docente.php";
include_once __DIR__ . "/Database.php";
include_once __DIR__ . "/HAE.php";

class Subscribe {
    private Docente $docente;
    public Database $db;
    private HAE $hae;

    public function __construct(Docente $docente, Database $db, HAE $hae) {
        $this->docente = $docente;
        $this->db = $db;
        $this->hae = $hae;
    }

    public function subToHAE(int $id_docente, int $id_hae, int $quant_hae, int $outras_fatecs, int $id_projeto): bool {
        $query = "INSERT INTO tb_inscricao (id_docente, id_hae, date_envio, quant_hae, outras_fatecs, id_projeto) VALUES (?, ?, NOW(), ?, ?, ?)";
        $stmt = $this->db->get_PDO()->prepare($query);
        return $stmt->execute([$id_docente, $id_hae, $quant_hae, $outras_fatecs, $id_projeto]);
    }
}