<?php
session_start();
require __DIR__ . "/../server/controller/state.php";

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
  <meta name="description" content="Home Page">
  <title>Home | HAE Fatec Itapira</title>
  <link rel="stylesheet" href="../styles/global.css" />
  <link rel="stylesheet" href="../styles/components.css" />
  <link rel="stylesheet" href="../styles/home.css" />
</head>

<body>
  <header-sp></header-sp>
  <header-fatec data-button-title="Sair" data-button-href="#"></header-fatec>

  <section>
    <main>
      <div class="card-primary">
        <div
          class="card-primary-banner"
          style="--gradient-1: var(--fatec-blue-500); --gradient-2: var(--fatec-blue-700)">
          <img src="../public/icons/home.svg" alt="" />
        </div>

        <span>
          <h2>Edital</h2>
          <p>Visualize os resultados da sua inscrição aqui</p>
        </span>

        <a href="./edital.php">
          <button
            class="button-secondary"
            style="
                --button-color: var(--fatec-blue-500);
                --button-color-hover: var(--fatec-blue-700);
              ">
            Visualizar
          </button>
        </a>
      </div>

      <div class="card-primary">
        <div
          class="card-primary-banner"
          style="--gradient-1: var(--fatec-red-700); --gradient-2: var(--fatec-red-500)">
          <img src="../public/icons/job.svg" alt="" />
        </div>

        <span>
          <h2>Vagas</h2>
          <p>Confira as vagas disponiveis na unidade de Itapira</p>
        </span>

        <a href="./haes.php">
          <button
            class="button-secondary"
            style="
                --button-color: var(--fatec-red-500);
                --button-color-hover: var(--fatec-red-400);
              ">
            Visualizar
          </button>
        </a>
      </div>
    </main>

    <div class="cards-container">
      <div>
        <a href="./me/inscricoes.php">
          <div class="card-secondary">
            <h2>Inscrições</h2>
            <p>Visualize HAEs inscritas</p>
          </div>
        </a>

        <a href="./me/relatorios.php">
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

  <rodape-sp></rodape-sp>
  <script src="../components/header.js" defer></script>
  <script src="../components/rodapeSP.js" defer></script>
  <script src="../components/headerSP.js" defer></script>
</body>

</html>