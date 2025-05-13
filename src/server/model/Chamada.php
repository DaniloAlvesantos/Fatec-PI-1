<?php

include_once __DIR__ . "/Database.php";

class Chamada {
    private int $id_chamada;
    private int $id_hae;
    private int $id_inscricao;
    public string $data_envio;

    public function __construct(int $id_chamada, int $id_hae, int $id_inscricao, string $data_envio) {
        $this->id_chamada = $id_chamada;
        $this->id_hae = $id_hae;
        $this->id_inscricao = $id_inscricao;
        $this->data_envio = $data_envio;
    }

    public function getIdChamada(): int {
        return $this->id_chamada;
    }

    public function getIdHAE(): int {
        return $this->id_hae;
    }

    public function getIdInscricao(): int {
        return $this->id_inscricao;
    }

    public function getDataEnvio(): string {
        return $this->data_envio;
    }
}