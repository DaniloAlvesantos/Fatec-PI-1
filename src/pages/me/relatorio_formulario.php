<?php
session_start();
require __DIR__ . "/../../server/controller/state.php";
require __DIR__ . "/../../server/model/Relatorio.php";

if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  exit();
}

if (isset($_GET["id"])) {
  $id = intval($_GET["id"]);
  $relatorio = new Relatorio();
  $relatorioData = $relatorio->getRelatorioForm($id);

  if (!$relatorioData) {
    header("Location: ./relatorios.php");
    exit();
  }

  $descricoes = json_decode($relatorioData["descricoes"], true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $message = "";
  if (isset($_FILES["documento_relatorio"]) && $_FILES["documento_relatorio"]["error"] !== UPLOAD_ERR_NO_FILE) {
    $arquivo = $_FILES["documento_relatorio"];
  } else {
    echo json_encode(["error" => "Erro ao enviar o arquivo."]);
    exit();
  }

  $descricoesFormulario = [
    "aproveitamento" => $_POST["aproveitamento"] ?? "",
    "resultados" => $_POST["resultados"] ?? "",
    "anotacoes" => $_POST["anotacoes"] ?? ""
  ];

  $resultado = $relatorio->createRelatorio($arquivo, $id, $descricoesFormulario, $_SESSION["user"]["id_docente"]);
  if (isset($resultado["error"])) {
    $message = '<div class="warn-container" style="background-color:#ffe0e0; border:1px solid #ff5c5c; padding:15px; border-radius:8px; color:#b30000; font-family:Arial, sans-serif;">
                  <h3 style="margin:0 0 8px 0;">Oops</h3>
                  <span>' . $resultado["error"] . '</span>
                </div>';
  } else {
    $message = '<div class="warn-container" style="background-color:#e0ffe0; border:1px solid #5cb85c; padding:15px; border-radius:8px; color:#2d862d; font-family:Arial, sans-serif;">
                  <h3 style="margin:0 0 8px 0;">Sucesso</h3>
                  <span>Relatório enviado com sucesso!</span>
                </div>';
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Preencha o formulário de relatório">
  <title>Formulário HAE | HAE Fatec Itapira</title>
  <link rel="stylesheet" href="../../styles/components.css" />
  <link rel="stylesheet" href="../../styles/formulario.css" />
  <link rel="stylesheet" href="../../styles/global.css" />
  <style>
    .form-field-column p {
      font-family: "Roboto", sans-serif;
      font-weight: 500;
      margin: 0.2rem 1rem;
    }
  </style>
</head>

<body>
  <header-fatec
    data-button-title="Voltar"
    data-button-href="./relatorios.php"></header-fatec>

  <?php if (isset($message)) echo $message; ?>

  <section id="hae-section">
    <div id="hae-container">
      <div>
        <h1><?php echo $relatorioData["haeTitulo"]; ?></h1>
        <p>Curso: <?php echo $relatorioData["tip_hae"]; ?></p>
      </div>
      <span class="hae-container-header-info">
        <img
          src="../../public/icons/calendar-clock.svg"
          alt="Ícone de calendário com relógio" />
        <p><?php echo date("d/m", strtotime($relatorioData["data_inicio"])) . " - " . date("d/m", strtotime($relatorioData["data_final"])); ?></p>
      </span>
    </div>

    <div id="about-container">
      <h3>Info:</h3>
      <ul>
        <li>
          <strong>Resultado esperado:</strong> <?php echo $descricoes["resultado_esperado"]; ?>
        </li>
        <li>
          <strong>Metodologia:</strong> <?php echo $descricoes["metodologia"]; ?>
        </li>
        <li>
          <strong>Cronograma:</strong>
          <?php echo $descricoes["cronograma"]; ?>
        </li>
      </ul>
    </div>
  </section>

  <hr />

  <section id="form-section">
    <h2 class="form-title">Formulário de relatório</h2>
    <form onsubmit="handleSubmit(event)" id="form-subscription" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id  ?>" enctype="multipart/form-data">
      <div class="form-container">
        <span class="form-field-column">
          <label for="aprov">Aproveitamento</label>
          <textarea
            oninput="autoSize(this)"
            class="textarea-primary"
            id="aprov"
            name="aproveitamento"
            placeholder="Diga o Aproveitamento do projeto"></textarea>
        </span>

        <span class="form-field-column">
          <label for="resultado">Resultados Atingidos</label>
          <textarea
            oninput="autoSize(this)"
            class="textarea-primary"
            id="resultado"
            name="resultados"
            placeholder="Diga os resultados atingidos"></textarea>
        </span>
      </div>

      <div class="form-container">
        <span class="form-field-column">
          <label for="anotacoes">Anotações</label>
          <textarea
            oninput="autoSize(this)"
            class="textarea-primary"
            id="anotacoes"
            name="anotacoes"
            placeholder="Suas anotações"></textarea>
        </span>

        <span class="form-field-column" id="fileField">
          <p>Documento PDF</p>
          <label for="documento" class="input-arq" id="file-container">
            <img src="../../public/icons/upload.svg" alt="" id="icon-file" />
            <input
              hidden
              type="file"
              name="documento_relatorio"
              id="documento"
              onchange="handleFile(this)" />
            Upload
          </label>
        </span>
        <p id="file-message"></p>
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
      <p class="error-form"></p>
    </form>
  </section>

  <script src="../../scripts/form-relatorio.js" defer></script>
  <script src="../../components/header.js" defer></script>
</body>

</html>