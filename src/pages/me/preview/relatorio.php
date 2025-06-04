<?php
session_start();
require_once __DIR__ . "/../../../server/model/HAE.php";
require_once __DIR__ . "/../../../server/model/Inscricao.php";
require_once __DIR__ . "/../../../server/model/Projeto.php";
require_once __DIR__ . "/../../../server/model/Relatorio.php";
require_once __DIR__ . "/../../../server/model/Feedback.php";

if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  exit();
}

require __DIR__ . "/../../../server/controller/state.php";

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $id = intval($id);

  $relatorio = new Relatorio();
  $hae = new HAE();
  $projeto = new Projeto();

  $relatorioData = $relatorio->getRelatorioWithFeedback($id);

  if (!$relatorioData) {
    header("Location: ../relatorios.php");
    exit();
  }

  $hae = $hae->getHAEById($relatorioData['id_hae']);
  $projeto = $projeto->getProjetoById($relatorioData['id_projeto']);
  $descricoes = json_decode($projeto->descricoes, true);
  $relatorioDescricoes = json_decode($relatorioData["descricoes"], true);

  $referer = $_SERVER["HTTP_REFERER"] ?? $_SERVER['REQUEST_URI'];
  $marker = '/src/';
  $pos = strpos($referer, $marker);
  $pdf_url_path = substr($referer, 0, $pos + strlen($marker)) . "server/assets/uploads/relatorios/" . $relatorioData["docenteId"] . "/" . $relatorioData["pdf_nome"];

  $feedbackStatus = null;
  $feedbackStatus = $relatorio->calcRelatorioStatusByFeedbacks($relatorio->getIdRelatorioByInscricao($id));
} else {
  header("Location: ../relatorios.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Seu relatório">
  <title>Formulario HAE | HAE Fatec Itapira</title>
  <link rel="stylesheet" href="../../../styles/components.css" />
  <link rel="stylesheet" href="../../../styles/formulario.css" />
  <link rel="stylesheet" href="../../../styles/global.css" />
  <style>
    .form-field-column p {
      font-family: "Roboto", sans-serif;
      font-weight: 500;
      margin: 0.2rem 1rem;
    }

    #file-message {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      max-width: 15rem;
    }
  </style>
</head>

<body>
  <header-fatec
    data-button-title="Voltar"
    data-button-href="../relatorios.php"></header-fatec>

  <section id="hae-section">
    <div id="hae-container">
      <div>
        <h1><?php echo $relatorioData["haeTitulo"]; ?></h1>
        <p>Curso: <?php echo $relatorioData["tip_hae"]; ?></p>
      </div>
      <span class="hae-container-header-info">
        <img
          src="../../../public/icons/calendar-clock.svg"
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
    <h2 class="form-title">Visualização relatório</h2>
    <form onsubmit="handleSubmit(event)" id="form-subscription">
      <div class="form-container">
        <span class="form-field-column">
          <label for="aprov">Aproveitamento</label>
          <textarea
            oninput="autoSize(this)"
            class="textarea-primary"
            id="aprov"
            name="aprov"
            placeholder="Diga o Aproveitamento do projeto"
            disabled><?php echo $relatorioDescricoes["aproveitamento"] ?></textarea>
        </span>

        <span class="form-field-column">
          <label for="resultado">Resultados Atingidos</label>
          <textarea
            oninput="autoSize(this)"
            class="textarea-primary"
            id="resultado"
            name="resultado"
            placeholder="Diga os resultados atingidos"
            disabled><?php echo $relatorioDescricoes["resultados"] ?></textarea>
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
            placeholder="Suas anotações"
            disabled><?php echo $relatorioDescricoes["anotacoes"] ?></textarea>
        </span>

        <span class="form-field-column" id="fileField">
          <p>Documento PDF</p>
          <label
            for="documento"
            class="input-arq"
            id="file-container"
            onclick="handleDownload('<?php echo $pdf_url_path ?>')">
            <img src="../../../public/icons/file.svg" alt="" id="icon-file" />
            <input
              hidden
              type="file"
              name="documento"
              id="documento"
              onload="createPreview(this)"
              value=""
              disabled />
            Download
          </label>
        </span>
        <a href="<?php echo $pdf_url_path ?>" target="_blank">
          <p id="file-message">Visualizar: <?php echo $relatorioData["pdf_original_nome"] ?>.pdf</p>
        </a>
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

  <?php
  echo "<hr />";
  if (count($relatorioData["feedbacks"])) {
    $emoji = $feedbackStatus["feedbackMessage"] === "Reprovada" ? "&#128533;" : "&#128578;";

    echo '<article class="status-container">
        <span class="status-header">
          <h2>Status: ' . $feedbackStatus["feedbackMessage"] . ' ' . $emoji . '</h2>
          <p>' . $feedbackStatus["plusMessage"] . '</p>
        </span>';

    echo '
        <main class="status-main">
          <h3>Observações:</h3>';

    foreach ($feedbackStatus["feedbacks"] as $feedback) {
      foreach ($feedback->comentarios as $comentario) {
        echo '<div class="status-comment" style="margin:1.5rem 0;
  max-width: 55%; min-width:15rem; border: 1px solid #ddd; border-radius: 8px; padding:1rem;">
            <img src="../../../public/icons/user.svg" alt="" />
            <span>' . $comentario->docente_info->cargo . " " . explode(" ", $comentario->docente_info->nome)[0] . ':</span>
            <p class="status-message">' . $comentario->comentario_text . '</p>
            <span class="status-footer">
            <p class="status-' . $feedback->resultado . '">' . $feedback->resultado . '</p> ' . ' - ' . '
            <p class="status-date">' . date("d/m/Y", strtotime($feedback->data_envio)) . '</p>
            </span>
          </div>';
      }
    }

    echo '
        </main>
      </article>';
  } else {
    echo '<article class="status-container">
        <span class="status-header">
          <h2>Status: Pendente</h2>
        </span>
        <main class="status-main">
          <p>Aguardando avaliação...</p>
        </main>
      </article>';
  }

  if (isset($feedbackStatus["feedbackMessage"]) && $feedbackStatus["feedbackMessage"] === "Aprovada") {
    echo '
      <script>
        confetti({
          particleCount: 150,
          spread: 90,
          origin: { y: 0.6 }
        });
      </script>
    ';
  }
  ?>
  <script src="../../../scripts/form-relatorio.js" defer></script>
  <script src="../../../components/header.js" defer></script>
</body>

</html>