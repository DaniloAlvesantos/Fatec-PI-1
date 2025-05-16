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
    <meta name="description" content="Formulário Edital">
    <title>Edital Form | HAE Fatec Itapira</title>
    <link rel="stylesheet" href="../../../styles/global.css" />
    <link rel="stylesheet" href="../../../styles/components.css" />
    <link rel="stylesheet" href="../../../styles/formulario.css" />
  </head>
  <body>
    <header-fatec
      data-button-title="Voltar"
      data-button-href="../editals.admin.php"
    ></header-fatec>

    <section id="form-section">
      <h2 class="form-title" style="text-align: center">
        Formulario edital de aprovação
      </h2>
      <form onsubmit="handleSubmit(event)" id="form-subscription">
        <span class="form-field-column">
          <label for="chamada">Selecione a chamada</label>
          <select name="chamada" id="chamada">
            <option value="1">1° Chamada</option>
            <option value="2">2° Chamada</option>
          </select>
        </span>

        <span class="form-field-column">
          <label for="semestre">Selecione o semestre</label>
          <select name="semestre" id="semestre">
            <option value="1">1° Semestre</option>
            <option value="2">2° Semestre</option>
          </select>
        </span>

        <div style="grid-column: 1/3; margin: 1rem;">
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
        </div>
      </form>
    </section>

    <script src="../../../components/header.js" defer></script>
  </body>
</html>
