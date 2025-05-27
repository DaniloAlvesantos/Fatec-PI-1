<?php

function returnSQLTables()
{
    return "
    CREATE TABLE tb_docente (
        id_docente INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(60) NOT NULL,
        RG VARCHAR(11) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        matricula INT UNIQUE NOT NULL,
        turno VARCHAR(8) NOT NULL,
        senha VARCHAR(25) NOT NULL,
        cargo ENUM('Professor', 'Coordenador', 'Secretaria', 'Diretor') NOT NULL,
        outras_fatecs INT,
        curso VARCHAR(4)
    );

    CREATE TABLE tb_hae (
        id_hae INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(60) NOT NULL,
        tip_hae VARCHAR(30) NOT NULL,
        quant_hae INT NOT NULL,
        descricao VARCHAR(500) NOT NULL,
        data_inicio DATE NOT NULL,
        data_final DATE NOT NULL
    );

    CREATE TABLE tb_projeto (
        id_projeto INT AUTO_INCREMENT PRIMARY KEY,
        data_inicio DATE NOT NULL,
        data_final DATE NOT NULL,
        titulo VARCHAR(40) NOT NULL,
        id_hae INT,
        descricoes JSON,
        dias_exec JSON
        FOREIGN KEY (id_hae) REFERENCES tb_hae(id_hae)
    );

    CREATE TABLE tb_inscricao (
        id_inscricao INT AUTO_INCREMENT PRIMARY KEY,
        id_docente INT,
        id_hae INT,
        data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
        quant_hae INT,
        outras_fatecs INT,
        id_projeto INT,
        status ENUM('Pendente', 'Em análise', 'Aprovada', 'Reprovada') NOT NULL DEFAULT 'Pendente',
        FOREIGN KEY (id_docente) REFERENCES tb_docente(id_docente),
        FOREIGN KEY (id_hae) REFERENCES tb_hae(id_hae),
        FOREIGN KEY (id_projeto) REFERENCES tb_projeto(id_projeto)
        CONSTRAINT uq_docente_hae UNIQUE (id_docente, id_hae)
    );

    CREATE TABLE tb_banco_de_horas (
        id_bdhrs INT AUTO_INCREMENT PRIMARY KEY,
        dias VARCHAR(4),
        turno VARCHAR(8),
        horas VARCHAR(6),
        id_inscricao INT,
        FOREIGN KEY (id_inscricao) REFERENCES tb_inscricao(id_inscricao)
    );

    CREATE TABLE tb_chamada (
        id_chamada INT AUTO_INCREMENT PRIMARY KEY,
        id_hae INT,
        id_inscricao INT,
        data_envio DATETIME NOT NULL,
        FOREIGN KEY (id_hae) REFERENCES tb_hae(id_hae),
        FOREIGN KEY (id_inscricao) REFERENCES tb_inscricao(id_inscricao)
    );

    CREATE TABLE tb_feedback (
        id_feedback INT AUTO_INCREMENT PRIMARY KEY,
        id_inscricao INT NOT NULL,
        data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
        resultado ENUM('Aprovada', 'Reprovada'),
        FOREIGN KEY (id_inscricao) REFERENCES tb_inscricao(id_inscricao)
    );

    CREATE TABLE tb_feedback_comentario (
        id_comentario INT AUTO_INCREMENT PRIMARY KEY,
        id_feedback INT NOT NULL,
        cargo ENUM('Coordenador', 'Diretor') NOT NULL,
        id_docente INT NOT NULL,
        comentario TEXT NOT NULL,
        FOREIGN KEY (id_feedback) REFERENCES tb_feedback(id_feedback),
        FOREIGN KEY (id_docente) REFERENCES tb_docente(id_docente)
    );

    CREATE TABLE tb_relatorio (
        id_relatorio INT AUTO_INCREMENT PRIMARY KEY,
        id_projeto INT,
        data_entrega DATETIME,
        pdf_url TEXT,
        pdf_nome VARCHAR(100),
        id_feedback INT,
        descricoes JSON,
        FOREIGN KEY (id_projeto) REFERENCES tb_projeto(id_projeto),
        FOREIGN KEY (id_feedback) REFERENCES tb_feedback(id_feedback)
    );
    ";
}


enum TablesEnum: string
{
    case docente = "tb_docente";
    case hae = "tb_hae";
    case chamada = "tb_chamada";
    case inscricao = "tb_inscricao";
    case banco_de_horas = "tb_banco_de_horas";
    case projeto = "tb_projeto";
    case relatorio = "tb_relatorio";
    case feedback = "tb_feedback";

    public function get_enum($enum): TablesEnum
    {
        return $this[$enum];
    }
}
