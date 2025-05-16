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
    <meta name="description" content="Confira os resultados no edital">
    <title>Painel | HAE Fatec Itapira</title>
    <link rel="stylesheet" href="../styles/global.css" />
    <link rel="stylesheet" href="../styles/components.css" />
    <link rel="stylesheet" href="../styles/edital.css" />
  </head>
  <body>
    <header-fatec
      data-button-title="Voltar"
      data-button-href="./home.php"
    ></header-fatec>

    <h1>Resultados das inscrições</h1>

    <section>
      <div class="card-secondary">
        <span>
          <h4>Primeira Chamada</h4>
          <h5>1° Semestre de 2025</h5>
          <p>
            <img
              src="../public/icons/calendar-clock.svg"
              alt="calendar clock"
            />15/02/2025
          </p>
        </span>
        <a href="./me/preview/edital.resultado.php">
          <button
            class="button-primary"
            style="
              --button-color: var(--fatec-red-500);
              --button-color-hover: var(--fatec-red-400);
            "
          >
            Conferir
          </button>
        </a>
      </div>
    </section>

    <script src="../components/header.js" defer></script>
  </body>
</html>
