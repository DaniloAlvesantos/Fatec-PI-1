<?php 

include_once __DIR__ . "/Database.php";

class Inscricao {
    private int $id_inscricao;
    private int $id_docente;
    private int $id_hae;
    public string $data_envio;
    public int $quant_hae;
    public int $outras_fatecs;
    private int $id_projeto;

    public function __construct(int $id_inscricao, int $id_docente, int $id_hae, string $data_envio, int $quant_hae, int $outras_fatecs, int $id_projeto) {
        $this->id_inscricao = $id_inscricao;
        $this->id_docente = $id_docente;
        $this->id_hae = $id_hae;
        $this->data_envio = $data_envio;
        $this->quant_hae = $quant_hae;
        $this->outras_fatecs = $outras_fatecs;
        $this->id_projeto = $id_projeto;
    }

    public function getIdInscricao(): int {
        return $this->id_inscricao;
    }

    public function getIdDocente(): int {
        return $this->id_docente;
    }

    public function getIdHAE(): int {
        return $this->id_hae;
    }

    public function getDataEnvio(): string {
        return $this->data_envio;
    }

    public function getQuantHAE(): int {
        return $this->quant_hae;
    }

    public function getOutrasFatecs(): int {
        return $this->outras_fatecs;
    }

    public function getIdProjeto(): int {
        return $this->id_projeto;
    }
}