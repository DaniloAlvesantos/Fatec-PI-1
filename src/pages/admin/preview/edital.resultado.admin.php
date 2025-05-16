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
    <meta name="description" content="Edital Preview">
    <title>Edital Admin | HAE Fatec Itapira</title>
    <link rel="stylesheet" href="../../../styles/global.css" />
    <link rel="stylesheet" href="../../../styles/components.css" />
    <link rel="stylesheet" href="../../../styles/edital.resultado.css" />
  </head>
  <body>
    <header-fatec
      data-button-title="Voltar"
      data-button-href="../editals.admin.php"
    ></header-fatec>

    <span class="title">
      <h1>Resultado: 1° Semestre de 2025</h1>
      <h2>Primeira Chamada</h2>
    </span>

    <section>
      <div class="table-container">
        <table class="hae-table">
          <thead>
            <tr>
              <th>HAE</th>
              <th>Projeto</th>
              <th>Professor</th>
              <th>C.H Solicitada</th>
              <th>C.H Deferidas</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Estágio Supervisionado</td>
              <td>Meu Estágio</td>
              <td>Júnior</td>
              <td>4</td>
              <td>3</td>
              <td><strong>Deferido</strong></td>
            </tr>
            <tr>
              <td>Pesquisa Científica</td>
              <td>Projeto de IA</td>
              <td>Maria</td>
              <td>6</td>
              <td>5</td>
              <td><strong>Indeferido</strong></td>
            </tr>
            <tr>
              <td>Trabalho de Graduação</td>
              <td>Meu TCC 100%</td>
              <td>João</td>
              <td>5</td>
              <td>5</td>
              <td><strong>Indeferido</strong></td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <script src="../../../components/header.js" defer></script>
  </body>
</html>
