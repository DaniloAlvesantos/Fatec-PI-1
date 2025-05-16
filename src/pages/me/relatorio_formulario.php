<?php
session_start();
require __DIR__ . "/../../server/controller/state.php";

if(!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Preencha o formulário de ralatório">
    <title>Formulario HAE | HAE Fatec Itapira</title>
    <link rel="stylesheet" href="../../styles/components.css" />
    <link rel="stylesheet" href="../../styles/formulario.css" />
    <link rel="stylesheet" href="../../styles/global.css" />
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
      data-button-href="./relatorios.php"
    ></header-fatec>

    <section id="hae-section">
      <div id="hae-container">
        <div>
          <h1>Monitoramento de estágio</h1>
          <p>Curso: DSM</p>
        </div>
        <span class="hae-container-header-info">
          <img
            src="../../public/icons/calendar-clock.svg"
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
            ></textarea>
          </span>

          <span class="form-field-column">
            <label for="resultado">Resultados Atingidos</label>
            <textarea
              oninput="autoSize(this)"
              class="textarea-primary"
              id="resultado"
              name="resultado"
              placeholder="Diga os resultados atingidos"
            ></textarea>
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
            ></textarea>
          </span>

          <span class="form-field-column" id="fileField">
            <p>Documento PDF</p>
            <label for="documento" class="input-arq" id="file-container">
              <img src="../../public/icons/upload.svg" alt="" id="icon-file" />
              <input
                hidden
                type="file"
                name="documento"
                id="documento"
                onchange="handleFile(this)"
              />
              Upload
            </label>
          </span>
          <p id="file-message"></p>
        </div>

        <button
          type="submit"
          id="submitButton"
          class="button-primary"
          style="
            --button-color: var(--fatec-red-500);
            --button-color-hover: var(--fatec-red-400);
          "
        >
          Enviar
        </button>
        <p class="error-form"></p>
      </form>
    </section>

    <script src="../../scripts/form-relatorio.js" defer></script>
    <script src="../../components/header.js" defer></script>
  </body>
</html>
