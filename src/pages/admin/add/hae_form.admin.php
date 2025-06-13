<?php
session_start();
require __DIR__ . "/../../../server/controller/state.php";

if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require_once __DIR__  . "/../../../server/model/HAE.php";
  $hae = new HAE();
  $message = "";
  $titulo = $_POST["titulo-hae"];
  $curso = $_POST["tip_hae"];
  $quantidade = $_POST["quant_hae"];
  $data_inicio = $_POST["data-inicio"];
  $data_finalizacao = $_POST["data-final"];
  $descricao = $_POST["descricao"];

  if (empty($titulo) || empty($curso) || empty($quantidade) || empty($data_inicio) || empty($data_finalizacao) || empty($descricao)) {
    echo $message = "Preencha os campos!";
  } else {
    if ($hae->createHAE($titulo, $descricao, $data_inicio, $data_finalizacao, $quantidade, $curso)) {
      $message = '<div class="warn-container" style="background-color:#e0ffe0; border:1px solid #5cb85c; padding:15px; border-radius:8px; color:#2d862d; font-family:Arial, sans-serif;">
                  <h3 style="margin:0 0 8px 0;">Sucesso</h3>
                  <span>Relatório enviado com sucesso!</span>
                </div>';
    } else {
      $message = '<div class="warn-container" style="background-color:#ffe0e0; border:1px solid #ff5c5c; padding:15px; border-radius:8px; color:#b30000; font-family:Arial, sans-serif;">
                  <h3 style="margin:0 0 8px 0;">Oops</h3>
                  <span>Algo deu errado :/</span>
                </div>';
    };
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="HAE Formulário Admin">
  <title>Formulario HAE Admin | HAE Fatec Itapira</title>
  <link rel="stylesheet" href="../../../styles/components.css" />
  <link rel="stylesheet" href="../../../styles/formulario.css" />
  <link rel="stylesheet" href="../../../styles/global.css" />
</head>

<body>
  <header-fatec
    data-button-title="Voltar"
    data-button-href="../haes.admin.php"></header-fatec>

    <?php echo $message; ?>

  <section id="hae-section">
    <div id="hae-container">
      <div>
        <h1 id="hae-title">Seu titulo</h1>
        <span>Curso: <strong id="hae-course"></strong></span>
      </div>
      <div class="hae-container-header">
        <span class="hae-container-header-info">
          <img src="../../../public/icons/hourglass.svg" alt="" />
          <p>Quantidade HAE: <strong id="hae-quant">0</strong></p>
        </span>
        <span class="hae-container-header-info">
          <img src="../../../public/icons/calendar-clock.svg" alt="" />
          <p><strong id="hae-data-inicio">00/01</strong> - <strong id="hae-data-finalizacao">00/02</strong></p>
        </span>
      </div>
    </div>

    <div id="about-container">
      <h3>Sobre a vaga</h3>
      <p id="hae-about">
        Lorem Ipsum is simply dummy text of the printing and typesetting
        industry. Lorem Ipsum has been the industry's standard dummy text ever
        ...
      </p>
    </div>
  </section>
  <hr />
  <section id="form-section">
    <h2 class="form-title">Formulario de inscrição</h2>
    <form onsubmit="handleSubmit(event)" id="form-subscription" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="form-container">
        <span class="form-field-column">
          <label for="titulo-hae">Título HAE</label>
          <input
            class="input-primary"
            type="text"
            id="titulo"
            name="titulo-hae"
            placeholder="Nome do seu projeto"
            oninput="setComp(this, 'hae-title')" />
        </span>

        <span class="form-field-column">
          <label for="curso-hae">Selecione o curso</label>
          <select name="tip_hae" id="curso-hae" onchange="setComp(this, 'hae-course')">
            <option value="" disabled selected>Selecionar</option>
            <option value="DSM">DSM</option>
            <option value="GPI">GPI</option>
            <option value="GTI">GTI</option>
            <option value="GE">GE</option>
          </select>
        </span>

        <span class="form-field-line">
          <input
            class="input-primary"
            type="number"
            id="quantidade-haes"
            name="quant_hae"
            max="10"
            placeholder="0"
            oninput="setComp(this, 'hae-quant')" />
          <label for="quantidade-haes">Quantidade de HAEs</label>
        </span>
      </div>

      <div class="form-container">
        <span class="form-field-column">
          <label for="data-inicio">Data de Início</label>
          <input
            class="input-primary"
            type="date"
            id="data-inicio"
            name="data-inicio"
            oninput="setComp(this, 'hae-data-inicio')"
            placeholder="Data início" />
        </span>

        <span class="form-field-column">
          <label for="data-finalizacao">Data de Finalização</label>
          <input
            class="input-primary"
            type="date"
            id="data-finalizacao"
            name="data-final"
            oninput="setComp(this, 'hae-data-finalizacao')"
            placeholder="Data final" />
        </span>

        <span class="form-field-column">
          <label for="metas">Sobre a Vaga</label>
          <textarea
            oninput="autoSize(this); setComp(this, 'hae-about')"
            class="textarea-primary"
            id="metas"
            name="descricao"
            placeholder="Diga suas metas"></textarea>
        </span>
      </div>
      <button
        type="submit"
        id="submitButton"
        class="button-primary"
        style="
            --button-color: var(--fatec-red-500);
            --button-color-hover: var(--fatec-red-400);
          ">
        Enviar
      </button>
      <p class="error-form"><?php echo $error ?? ""; ?></p>
    </form>
  </section>

  <script src="../../../scripts/form-hae-admin.js" defer></script>
  <script src="../../../components/header.js" defer></script>
</body>

</html>