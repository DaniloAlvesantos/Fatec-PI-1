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
    <meta name="description" content="Confira os relatórios">
    <title></title>
    <link rel="stylesheet" href="../../styles/global.css" />
    <link rel="stylesheet" href="../../styles/components.css" />
    <link rel="stylesheet" href="../../styles/relatorios.css" />
  </head>
  <body>
    <header-fatec
      data-button-title="Voltar"
      data-button-href="../home.php"
    ></header-fatec>

    <section>
      <h2>Pendentes</h2>
      <hr />

      <div class="card-container">
        <div class="card-primary card-rel">
          <span class="card-rel-title">
            <h3 class="">Relatorio</h3>
          </span>

          <span class="card-rel-info">
            <p>Monitoramento de estágio</p>
            <p>Projeto: Meu estágio</p>
            <p>Curso: DSM</p>
          </span>

          <span class="card-rel-date">
            <img src="../../public/icons/calendar-clock.svg" alt="calendario" />
            <p>20/12</p>
          </span>

          <a href="./relatorio_formulario.php">
            <button
              class="button-primary"
              style="
                --button-color: var(--fatec-red-500);
                --button-color-hover: var(--fatec-red-400);
              "
            >
              Realizar
            </button>
          </a>
        </div>
      </div>

      <h2>Enviados</h2>
      <hr />
      <div class="card-container">
        <div class="card-primary card-rel">
          <span class="card-rel-title">
            <h3 class="">Relatorio</h3>
          </span>

          <span class="card-rel-info">
            <p>Reforço de estudos</p>
            <p>Projeto: Estudos++</p>
            <p>Curso: DSM</p>
            <p>Status: <strong>Indeferido</strong></p>
          </span>

          <span class="card-rel-date">
            <img src="../../public/icons/calendar-clock.svg" alt="calendario" />
            <p>10/06</p>
          </span>

          <a href="./preview/relatorio.php">
            <button
              class="button-primary"
              style="
                --button-color: var(--fatec-red-500);
                --button-color-hover: var(--fatec-red-400);
              "
            >
              Visualizar
            </button>
          </a>
        </div>
      </div>
    </section>

    <script src="../../components/header.js" defer></script>
  </body>
</html>
