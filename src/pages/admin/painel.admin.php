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
    <meta name="description" content="Home Admin">
    <title>Home Admin | HAE Fatec Itapira</title>
    <link rel="stylesheet" href="../../styles/global.css" />
    <link rel="stylesheet" href="../../styles/components.css" />
    <link rel="stylesheet" href="../../styles/home.css" />
  </head>
  <body>
    <header-fatec data-button-title="Sair" data-button-href="#"></header-fatec>
    <section>
      <main>
        <div class="card-primary">
          <div
            class="card-primary-banner"
            style="
              --gradient-1: var(--fatec-blue-500);
              --gradient-2: var(--fatec-blue-700);
            "
          >
            <img src="../../public/icons/home.svg" alt="" />
          </div>

          <span>
            <h2>Edital</h2>
            <p>Lance as inscrições aprovadas aqui</p>
          </span>

          <a href="./editals.admin.php">
            <button
              class="button-secondary"
              style="
                --button-color: var(--fatec-blue-500);
                --button-color-hover: var(--fatec-blue-700);
              "
            >
              Visualizar
            </button>
          </a>
        </div>

        <div class="card-primary">
          <div
            class="card-primary-banner"
            style="
              --gradient-1: var(--fatec-red-700);
              --gradient-2: var(--fatec-red-500);
            "
          >
            <img src="../../public/icons/job.svg" alt="" />
          </div>

          <span>
            <h2>Vagas</h2>
            <p>Cadastre HAEs aqui</p>
          </span>

          <a href="./haes.admin.php">
            <button
              class="button-secondary"
              style="
                --button-color: var(--fatec-red-500);
                --button-color-hover: var(--fatec-red-400);
              "
            >
              Visualizar
            </button>
          </a>
        </div>
      </main>

      <div class="cards-container">
        <div>
          <a href="./inscricoes.admin.php">
            <div class="card-secondary">
              <h2>Inscrições</h2>
              <p>Visualize todas as inscrições</p>
            </div>
          </a>

          <a href="./relatorios.admin.php">
            <div class="card-secondary">
              <h2>Relátorios</h2>
              <p>Visualize relátorios enviados</p>
            </div>
          </a>

          <a href="">
            <div class="card-secondary">
              <h2>Atualize suas informações</h2>
              <p>Altere suas informações</p>
            </div>
          </a>
        </div>
      </div>
    </section>

    <script src="../../components/header.js" defer></script>
  </body>
</html>
