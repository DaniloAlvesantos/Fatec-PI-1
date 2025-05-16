<?php
session_start();
require __DIR__ . "/../../../server/controller/state.php";

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
    <meta name="description" content="Sua inscrição" />
    <title>Inscrição | HAE Fatec Itapira</title>
    <link rel="stylesheet" href="../../../styles/global.css" />
    <link rel="stylesheet" href="../../../styles/components.css" />
    <link rel="stylesheet" href="../../../styles/formulario.css" />
  </head>
  <body>
    <header-fatec
      data-button-title="Voltar"
      data-button-href="../inscricoes.php"
    ></header-fatec>

    <section id="hae-section">
      <div id="hae-container">
        <div>
          <h1>Monitoramento de estágio</h1>
          <p>Curso: DSM</p>
        </div>
        <div class="hae-container-header">
          <span class="hae-container-header-info">
            <img src="../../../public/icons/hourglass.svg" alt="" />
            <p>Quantidade HAE: 6</p>
          </span>
          <span class="hae-container-header-info">
            <img src="../../../public/icons/calendar-clock.svg" alt="" />
            <p>20/10 - 24/12</p>
          </span>
        </div>
      </div>

      <div id="about-container">
        <h3>Sobre a vaga</h3>
        <p>
          Lorem Ipsum is simply dummy text of the printing and typesetting
          industry. Lorem Ipsum has been the industry's standard dummy text ever
          since the 1500s, when an unknown printer took a galley of type and
          scrambled it to make a type specimen book. It has survived not only
          five centuries, but also the leap into electronic typesetting,
          remaining essentially unchanged. It was popularised in the 1960s with
          the release of Letraset sheets containing Lorem Ipsum passages, and
          more recently with desktop publishing software like Aldus PageMaker
          including versions of Lorem Ipsum. de acordo com o curriculo do curso
          e com sua carga horaria...
        </p>
      </div>
    </section>
    <hr />
    <section id="form-section">
      <h2 class="form-title">Formulario de inscrição</h2>
      <form id="form-subscription">
        <div class="form-container">
          <span class="checkfield">
            <input
              type="checkbox"
              id="outras-fatecs"
              name="outras-fatecs"
              checked
              disabled
            />
            <label for="outras-fatecs">Possui aulas em outras Fatecs?</label>
          </span>

          <span class="form-field-line">
            <input
              class="input-primary"
              type="number"
              id="quantidade-haes"
              name="quantidade-haes"
              max="10"
              value="4"
              disabled
            />
            <label for="quantidade-haes">Quantidade de HAEs</label>
          </span>

          <span class="form-field-column">
            <label for="titulo-projeto">Título do Projeto</label>
            <input
              class="input-primary"
              type="text"
              id="titulo-projeto"
              name="titulo-projeto"
              placeholder="Nome do seu projeto"
              value="Meu estágio minha vida"
              disabled
            />
          </span>

          <span class="form-field-column">
            <label for="data-inicio">Data de Início</label>
            <input
              class="input-primary"
              type="text"
              id="data-inicio"
              name="data-inicio"
              value="05/02/2025"
              disabled
            />
          </span>

          <span class="form-field-column"
            ><label for="data-finalizacao">Data de Finalização</label>
            <input
              class="input-primary"
              type="text"
              id="data-finalizacao"
              name="data-finalizacao"
              value="03/06/2025"
              disabled
          /></span>

          <span class="form-field-column"
            ><label>Dias de Execução</label>
            <p class="error"></p>
            <div id="container-inputs" class="form-field-column">
              <input
                class="input-primary"
                type="text"
                id="dia-execucao1"
                name="dia-execucao"
                autocomplete="off"
                placeholder="Segunda, Noite, 17-19"
                value="S,N,18-20"
                disabled
              />
              <input
                class="input-primary"
                type="text"
                id="dia-execucao2"
                name="dia-execucao"
                autocomplete="off"
                placeholder="Segunda, Noite, 17-19"
                value="T,N,15-18"
                disabled
              />
            </div>
          </span>

          <span class="form-field-column">
            <label for="metas">Metas</label>
            <textarea
              class="textarea-primary"
              type="text"
              id="metas"
              name="metas"
              placeholder="Diga suas metas"
              disabled
            >
Minha meta é fazer que todos os aluno consigam sua primeira vaga de estágio durante o primeiro semestre do ano de 2025
          </textarea
            >
          </span>
        </div>

        <div class="form-container">
          <span class="form-field-column"
            ><label for="objetivos">Objetivos</label>
            <textarea
              class="textarea-primary"
              type="text"
              id="objetivos"
              name="objetivos"
              placeholder="Diga seus objetivos"
              disabled
            >
Objetivos do projeto é fazer que a unidade seja reconhecida pelas empresa, qual os alunos tenham o melhor desempenho da região</textarea
            >
          </span>

          <span class="form-field-column"
            ><label for="justificativa">Justificativa</label>
            <textarea
              class="textarea-primary"
              type="text"
              id="justificativa"
              name="justificativa"
              placeholder="Justifique seu projeto"
              disabled
            >
Os alunos não conseguem ter uma margem de ação para conquistar um estágio</textarea
            >
          </span>

          <span class="form-field-column">
            <label for="recursos">Recursos, Materiais e Humanos</label>
            <input
              class="input-primary"
              type="text"
              id="recursos"
              name="recursos"
              placeholder="Sala, Mesa, Caderno..."
              value="Sala B2, Ar condicionado, Internet"
              disabled
            />
          </span>

          <span class="form-field-column">
            <label for="resultado-esperado">Resultado Esperado</label>
            <textarea
              class="textarea-primary"
              type="text"
              id="resultado-esperado"
              name="resultado-esperado"
              placeholder="Expectativas de resultado"
              disabled
            >
Ter pelo menos 60% dos alunos fazendo estágio</textarea
            >
          </span>

          <span class="form-field-column">
            <label for="metodologia">Metodologia</label>
            <textarea
              class="textarea-primary"
              type="text"
              id="metodologia"
              name="metodologia"
              placeholder="Medoto de realização"
              disabled
            >
Orientar e instruir os alunos a necessidade do estágio</textarea
            >
          </span>

          <span class="form-field-column">
            <label for="cronograma">Cronograma das Atividades</label>
            <textarea
              class="textarea-primary"
              id="cronograma"
              name="cronograma"
              placeholder="Agosto: Apresentação..."
              disabled
            >
Fev: Inicio
Mar: Instruções
...</textarea
            >
          </span>
        </div>
      </form>
    </section>

    <hr />

    <article class="status-container">
      <span class="status-header">
        <h2>Status: Indeferido &#128533;</h2>
        <p>Desta vez, você não conseguiu. Desta vez, tá!?</p>
      </span>

      <main>
        <h3>Observações:</h3>

        <div class="status-comment">
          <img src="../../../public/icons/user.svg" alt="" />
          <span>Coordenadora Marcia:</span>
          <p>
            Quantidade de HAEs solicitadas não encaixam com à quantidade de
            horas de aulas.
          </p>
        </div>
      </main>
    </article>

    <script src="../../../components/header.js" defer></script>
    <script defer>
      document.querySelectorAll("textarea").forEach((element) => {
        element.style.height = element.scrollHeight + "px";
      });
    </script>
  </body>
</html>
