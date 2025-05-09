<?php

function returnSQLTables() {
    return "
    CREATE TABLE tb_docente (
        id_docente INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(60) NOT NULL,
        RG VARCHAR(10) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        matricula INT UNIQUE NOT NULL,
        DTH VARCHAR(8) NOT NULL,
        senha VARCHAR(25) NOT NULL,
        cargo ENUM('Professor', 'Coordenador', 'Secretaria') NOT NULL,
        outras_fatecs INT,
        curso VARCHAR(4)
    );

    CREATE TABLE tb_hae (
        id_hae INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(60) NOT NULL,
        tip_hae VARCHAR(30) NOT NULL,
        quant_hae INT NOT NULL,
        descricao VARCHAR(255) NOT NULL,
        id_inscricao INT
    );

    CREATE TABLE tb_chamada (
        id_chamada INT AUTO_INCREMENT PRIMARY KEY,
        id_hae INT,
        id_inscricao INT,
        data_envio DATETIME NOT NULL
    );

    CREATE TABLE tb_inscricao (
        id_inscricao INT AUTO_INCREMENT PRIMARY KEY,
        id_docente INT,
        id_hae INT,
        date_envio DATETIME NOT NULL,
        quant_hae INT,
        outras_fatecs INT,
        id_projeto INT,
        id_bdhrs INT
    );

    CREATE TABLE tb_banco_de_horas (
        id_bdhrs INT AUTO_INCREMENT PRIMARY KEY,
        dias JSON,
        turno JSON,
        horas JSON,
        id_inscricao INT
    );

    CREATE TABLE tb_projeto (
        id_projeto INT AUTO_INCREMENT PRIMARY KEY,
        data_inicio DATE NOT NULL,
        data_final DATE NOT NULL,
        titulo VARCHAR(40) NOT NULL,
        id_hae INT,
        descricoes JSON
    );

    CREATE TABLE tb_relatorio (
        id_relatorio INT AUTO_INCREMENT PRIMARY KEY,
        id_projeto INT,
        data_entrega DATETIME,
        pdf_url TEXT,
        id_feedback INT
    );

    CREATE TABLE tb_feedback (
        id_feedback INT AUTO_INCREMENT PRIMARY KEY,
        id_inscricao INT,
        date_envio DATETIME,
        descricao VARCHAR(255),
        id_coor INT,
        id_diretor INT,
        observacao VARCHAR(255),
        resultado ENUM('Aprovado', 'Reprovado')
    );
    ";
}

enum TablesEnum {
    case docente;
    case hae;
    case chamada;
    case inscricao;
    case banco_de_horas;
    case projeto;
    case relatorio;
    case feedback;
}

function returnTable($table) {
    echo math($table) {
        TablesEnum::docente => "tb_docente",
        TablesEnum::hae => "tb_hae",
        TablesEnum::chamada => "tb_chamada",
        TablesEnum::inscricao => "tb_inscricao",
        TablesEnum::banco_de_horas => "tb_banco_de_horas",
        TablesEnum::projeto => "tb_projeto",
        TablesEnum::relatorio => "tb_relatorio",
        TablesEnum::feedback => "tb_feedback",
    }
}