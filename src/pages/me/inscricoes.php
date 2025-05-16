<?php
session_start();
require __DIR__ . "/../../server/controller/state.php";

if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Confira suas inscrições">
  <title>Minhas incrições | HAE Fatec Itapira</title>
  <link rel="stylesheet" href="../../styles/global.css" />
  <link rel="stylesheet" href="../../styles/components.css" />
  <link rel="stylesheet" href="../../styles/haes.css" />
</head>

<body>
  <header-fatec
    data-button-title="Voltar"
    data-button-href="../home.php"></header-fatec>

  <section>
    <h1>Visualize suas inscrições</h1>
    <label for="search" class="search-container">
      <input
        type="text"
        id="search"
        class="input-secondary"
        placeholder="Pesquisar..." />
      <img
        src="../../public/icons/search.svg"
        alt="lupa"
        class="glass-search"
        onclick="handleSearch()" />
    </label>

    <main>
      <div class="card-hae card-primary">
        <span class="card-hae-title">
          <p class="card-hae-tag">H.A.E</p>
          <h3 class="">Fatec Itapira</h3>
        </span>

        <span class="card-hae-info">
          <p>Monitoramento de estágio</p>
          <p>Quantidade HAE: 6</p>
          <p>Curso: DSM</p>
          <p>Status: <strong>Indeferido</strong></p>
        </span>

        <span class="card-hae-date">
          <img src="../../public/icons/calendar-clock.svg" alt="calendario" />
          <p>25/10/2024</p>
        </span>

        <a href="./preview/inscricao.php">
          <button
            class="button-primary"
            style="
                --button-color: var(--fatec-red-500);
                --button-color-hover: var(--fatec-red-400);
              ">
            Visualizar
          </button>
        </a>
      </div>
    </main>
  </section>

  <script src="../../components/header.js" defer></script>
</body>

</html>