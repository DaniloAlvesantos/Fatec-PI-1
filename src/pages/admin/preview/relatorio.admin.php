<?php
session_start();
require __DIR__ . "/../../../server/controller/state.php";

if(!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  exit();
}

?><!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Realtoório enviados">
    <title>Relatório Admin | HAE Fatec Itapira</title>
    <link rel="stylesheet" href="../../../styles/components.css" />
    <link rel="stylesheet" href="../../../styles/formulario.css" />
    <link rel="stylesheet" href="../../../styles/global.css" />
    <style>
      .form-field-column p {
        font-family: "Roboto", sans-serif;
        font-weight: 500;
        margin: 0.2rem 1rem;
      }
    </style>
  </head>
  <body>
    <header-fatec
      data-button-title="Voltar"
      data-button-href="../relatorios.admin.php"
    ></header-fatec>

    <section id="hae-section">
      <div id="hae-container">
        <div>
          <h1>Monitoramento de estágio</h1>
          <p>Curso: DSM</p>
        </div>
        <span class="hae-container-header-info">
          <img
            src="../../../public/icons/calendar-clock.svg"
            alt="Ícone de calendário com relógio"
          />
          <p>05/08 - 20/12</p>
        </span>
      </div>

      <div id="about-container">
        <h3>Projeto:</h3>
        <ul>
          <li><strong>Nome:</strong> Meu estágio</li>
          <li>
            <strong>Resultado esperado:</strong> Ter pelo menos 60% dos alunos
            fazendo estágio
          </li>
          <li>
            <strong>Metodologia:</strong> Orientar e instruir os alunos sobre a
            necessidade do estágio
          </li>
          <li>
            <strong>Cronograma:</strong>
            Fev: Início Mar: Instruções ...
          </li>
        </ul>
      </div>
    </section>

    <hr />

    <section id="form-section">
      <h2 class="form-title">Formulário de relatório</h2>
      <form onsubmit="handleSubmit(event)" id="form-subscription">
        <div class="form-container">
          <span class="form-field-column">
            <label for="aprov">Aproveitamento</label>
            <textarea
              oninput="autoSize(this)"
              class="textarea-primary"
              id="aprov"
              name="aprov"
              placeholder="Diga o Aproveitamento do projeto"
              disabled
            >
Todos os alunos conseguiram pelos menos 2 estágios</textarea
            >
          </span>

          <span class="form-field-column">
            <label for="resultado">Resultados Atingidos</label>
            <textarea
              oninput="autoSize(this)"
              class="textarea-primary"
              id="resultado"
              name="resultado"
              placeholder="Diga os resultados atingidos"
              disabled
            >
Total de 200 estágios</textarea
            >
          </span>
        </div>

        <div class="form-container">
          <span class="form-field-column">
            <label for="anotacoes">Anotações</label>
            <textarea
              oninput="autoSize(this)"
              class="textarea-primary"
              id="anotacoes"
              name="anotacoes"
              placeholder="Suas anotações"
              disabled
            >
Aluno Lucas 0% de presença</textarea
            >
          </span>

          <span class="form-field-column" id="fileField">
            <p>Documento PDF</p>
            <label
              for="documento"
              class="input-arq"
              id="file-container"
              onclick="handleDownload('blob:http://127.0.0.1:5500/3e7b0565-c775-4e46-ad2b-c771f44781e7')"
            >
              <img src="../../../public/icons/file.svg" alt="" id="icon-file" />
              <input
                hidden
                type="file"
                name="documento"
                id="documento"
                disabled
              />
              Download
            </label>
          </span>
          <p id="file-message">relatório.pdf</p>
        </div>
      </form>
    </section>

    <hr />

    <article class="status-container">
      <form class="form-obs">
        <h3>Observações:</h3>

        <div class="status-comment">
          <img src="../../../public/icons/user.svg" alt="" />
          <span>Coordenadora Marcia:</span>
          <textarea
            name="observacao"
            id="observacao"
            class="textarea-primary"
            oninput="autoSize(this)"
            placeholder="Digite sua observação"
          ></textarea>
        </div>

        <div class="buttons">
          <button
            class="button-primary"
            style="
              --button-color: var(--fatec-blue-500);
              --button-color-hover: var(--fatec-blue-700);
            "
          >
            Aprovar
          </button>
          <button
            class="button-primary"
            style="
              --button-color: var(--fatec-red-500);
              --button-color-hover: var(--fatec-red-400);
            "
          >
            Reprovar
          </button>
        </div>
      </form>
    </article>

    <script src="../../../scripts/form-relatorio.js" defer></script>
    <script src="../../../components/header.js" defer></script>
  </body>
</html>
