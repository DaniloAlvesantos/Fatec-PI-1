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
    <meta name="description" content="Realtoórios enviados">
    <title>Relatórios | HAE Fatec Itapira</title>
    <link rel="stylesheet" href="../../styles/global.css" />
    <link rel="stylesheet" href="../../styles/components.css" />
    <link rel="stylesheet" href="../../styles/relatorios.css" />
  </head>
  <body>
    <header-fatec
      data-button-title="Voltar"
      data-button-href="./painel.admin.php"
    ></header-fatec>

    <section>
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
            <p>Status: <strong>Pendente</strong></p>
          </span>

          <span class="card-rel-date">
            <img src="../../public/icons/calendar-clock.svg" alt="calendario" />
            <p>10/06</p>
          </span>

          <a href="./preview/relatorio.admin.php">
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
