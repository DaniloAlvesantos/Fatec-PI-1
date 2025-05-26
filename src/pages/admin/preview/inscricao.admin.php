<?php
session_start();
require __DIR__ . "/../../../server/controller/state.php";
require_once __DIR__ . "/../../../server/model/HAE.php";
require_once __DIR__ . "/../../../server/model/Inscricao.php";
require_once __DIR__ . "/../../../server/model/Docente.php";
require_once __DIR__ . "/../../../server/model/Projeto.php";
require_once __DIR__ . "/../../../server/model/Feedback.php"; 

if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  exit();
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $id = intval($id);

  $hae = new HAE();
  $inscricao = new Inscricao();
  $projeto = new Projeto();
  $docente = new Docente();
  $feedback = new Feedback(); // Instantiate the Feedback class

  $inscricao = $inscricao->getMySubscriptionsById($id);
  $hae = $hae->getHAEById($inscricao->getIdHae());
  $projeto = $projeto->getProjetoById($inscricao->getIdProjeto());
  $docente = $docente->getDocenteById($inscricao->getIdDocente());
  $descricoes = json_decode($projeto->descricoes, true);

  // Fetch ALL feedback objects as an array
  $allFeedbacks = [];
  $latestFeedback = null;
  $feedbackCount = 0;
  
  if ($inscricao->status !== "Pendente") {
    $allFeedbacks = $feedback->getAllFeedbacksByInscricao($id);
    $latestFeedback = $feedback->getLatestFeedbackByInscricao($id);
    $feedbackCount = $feedback->countFeedbacksByInscricao($id);
  }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $observacao = $_POST["observacao"];
  $status = $_POST["status"];

  // Re-instantiate feedback to create a new one, as this is a POST request
  $newFeedback = new Feedback();
  $newFeedback->createFeedback($id, $status, $_SESSION["user"]["cargo"], $_SESSION["user"]["id_docente"], $observacao);

  $inscricao->setStatus($status); // Assuming setStatus updates the database for the inscription status
  // After updating the status, you might want to redirect or refresh to show the new feedback
  header("Location: " . $_SERVER['PHP_SELF'] . "?id=$id");
  exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Inscrição enviada">
  <title>Inscrição Admin | HAE Fatec Itapira</title>
  <link rel="stylesheet" href="../../../styles/global.css" />
  <link rel="stylesheet" href="../../../styles/components.css" />
  <link rel="stylesheet" href="../../../styles/formulario.css" />
</head>

<body>
  <header-fatec
    data-button-title="Voltar"
    data-button-href="../inscricoes.admin.php"></header-fatec>

  <section id="hae-section">
    <div id="hae-container">
      <div>
        <h1><?php echo $hae->titulo ?></h1>
        <p>Curso: <?php echo $hae->tip_hae ?></p>
      </div>
      <div class="hae-container-header">
        <span class="hae-container-header-info">
          <img src="../../../public/icons/hourglass.svg" alt="" />
          <p>Quantidade HAE: <?php echo $hae->quant_hae ?></p>
        </span>
        <span class="hae-container-header-info">
          <img src="../../../public/icons/calendar-clock.svg" alt="" />
          <p><?php echo date("d/m", strtotime($hae->data_inicio)); ?> - <?php echo date("d/m", strtotime($hae->data_final)); ?></p>
        </span>
      </div>
    </div>

    <div id="about-container">
      <h3>Sobre a vaga</h3>
      <p><?php echo $hae->descricao ?></p>
    </div>
  </section>
  <hr />
  <section id="form-section">
    <h2 class="form-title">Formulario do docente</h2>
    <form id="form-subscription">
      <div class="form-container">
        <span class="checkfield">
          <input
            type="checkbox"
            id="outras-fatecs"
            name="outras-fatecs"
            <?php echo intval($inscricao->outras_fatecs) === 1 ? htmlspecialchars("checked") : null; ?>
            disabled />
          <label for="outras-fatecs">Possui aulas em outras Fatecs?</label>
        </span>

        <span class="form-field-line">
          <input
            class="input-primary"
            type="number"
            id="quantidade-haes"
            name="quantidade-haes"
            value="<?php echo $inscricao->quant_hae ?>"
            disabled />
          <label for="quantidade-haes">Quantidade de HAEs</label>
        </span>

        <span class="form-field-column">
          <label for="titulo-projeto">Título do Projeto</label>
          <input
            class="input-primary"
            type="text"
            id="titulo-projeto"
            name="titulo-projeto"
            placeholder="Nome do seu projeto"
            value="<?php echo $projeto->titulo ?>"
            disabled />
        </span>

        <span class="form-field-column">
          <label for="data-inicio">Data de Início</label>
          <input
            class="input-primary"
            type="text"
            id="data-inicio"
            name="data-inicio"
            value="<?php echo date("d/m/Y", strtotime($projeto->data_inicio)) ?>"
            disabled />
        </span>

        <span class="form-field-column"><label for="data-finalizacao">Data de Finalização</label>
          <input
            class="input-primary"
            type="text"
            id="data-finalizacao"
            name="data-finalizacao"
            value="<?php echo date("d/m/Y", strtotime($projeto->data_final)) ?>"
            disabled /></span>

        <span class="form-field-column"><label>Dias de Execução</label>
          <p class="error"></p>
          <div id="container-inputs" class="form-field-column">
            <?php
            $p = json_decode($projeto->dias_exec);
            foreach ($p as $key => $value) {
              echo '<input
                  class="input-primary"
                  type="text"
                  id="dia-execucao'
                . ($key + 1) . '"
                  name="dia-execucao"
                  autocomplete="off"
                  placeholder="Segunda, Noite, 17-19"
                  value="' . htmlspecialchars($value) . '"
                  disabled />';
            }
            ?>
          </div>
        </span>

        <span class="form-field-column">
          <label for="metas">Metas</label>
          <textarea
            class="textarea-primary"
            type="text"
            id="metas"
            name="metas"
            placeholder="Diga suas metas"
            disabled><?php echo $descricoes["metas"] ?></textarea>
        </span>
      </div>

      <div class="form-container">
        <span class="form-field-column"><label for="objetivos">Objetivos</label>
          <textarea
            class="textarea-primary"
            type="text"
            id="objetivos"
            name="objetivos"
            placeholder="Diga seus objetivos"
            disabled><?php echo $descricoes["objetivos"] ?></textarea>
        </span>

        <span class="form-field-column"><label for="justificativa">Justificativa</label>
          <textarea
            class="textarea-primary"
            type="text"
            id="justificativa"
            name="justificativa"
            placeholder="Justifique seu projeto"
            disabled><?php echo $descricoes["justificativa"] ?></textarea>
        </span>

        <span class="form-field-column">
          <label for="recursos">Recursos, Materiais e Humanos</label>
          <input
            class="input-primary"
            type="text"
            id="recursos"
            name="recursos"
            placeholder="Sala, Mesa, Caderno..."
            value="<?php echo $descricoes["recursos"] ?>"
            disabled />
        </span>

        <span class="form-field-column">
          <label for="resultado-esperado">Resultado Esperado</label>
          <textarea
            class="textarea-primary"
            type="text"
            id="resultado-esperado"
            name="resultado-esperado"
            placeholder="Expectativas de resultado"
            disabled><?php echo $descricoes["resultado_esperado"] ?></textarea>
        </span>

        <span class="form-field-column">
          <label for="metodologia">Metodologia</label>
          <textarea
            class="textarea-primary"
            type="text"
            id="metodologia"
            name="metodologia"
            placeholder="Medoto de realização"
            disabled><?php echo $descricoes["metodologia"] ?></textarea>
        </span>

        <span class="form-field-column">
          <label for="cronograma">Cronograma das Atividades</label>
          <textarea
            class="textarea-primary"
            id="cronograma"
            name="cronograma"
            placeholder="Agosto: Apresentação..."
            disabled><?php echo $descricoes["cronograma"] ?></textarea>
        </span>
      </div>
    </form>
  </section>

  <hr />

  <article class="status-container">
    <?php
    // Display feedback count if there are multiple feedbacks
    if ($feedbackCount > 0) {
        echo '<div class="feedback-summary">';
        echo '<h3>Histórico de Avaliações (' . $feedbackCount . ' avaliação' . ($feedbackCount > 1 ? 'ões' : '') . ')</h3>';
        echo '</div>';
    }

    // Display all feedbacks in chronological order
    if (!empty($allFeedbacks) && $inscricao->status !== "Pendente") {
      foreach ($allFeedbacks as $index => $feedbackData) {
        $class = "status-" . strtolower($feedbackData->resultado);
        $feedbackNumber = $index + 1;
        
        echo '
          <div class="feedback-item" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
            <div class="feedback-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
              <h4>Avaliação #' . $feedbackNumber . '</h4>
              <div class="status-result status-result-' . strtolower($feedbackData->resultado) . '">
                <span class="status-label ' . $class . '">Status: ' . htmlspecialchars($feedbackData->resultado) . '</span>
                <span class="status-date">Data: ' . date("d/m/Y H:i", strtotime($feedbackData->data_envio)) . '</span>
              </div>
            </div>
            
            <div class="feedback-comments">
              <h5>Comentários:</h5>';
        
        // Loop through each comment associated with this feedback
        foreach ($feedbackData->comentarios as $comment) {
          echo '
              <div class="status-comment" style="margin-bottom: 10px;">
                  <img src="../../../public/icons/user.svg" alt="" />
                  <span>' . htmlspecialchars($comment->cargo) . " " . htmlspecialchars(explode(" ", $comment->docente_info->nome)[0]) . ':</span>
                  <textarea
                      class="textarea-primary"
                      disabled
                      oninput="autoSize(this)">' . htmlspecialchars($comment->comentario_text) . '</textarea>
              </div>';
        }
        
        echo '
            </div>
          </div>';
      }
    }
    ?>
    
    <!-- Form for new feedback -->
    <form class="form-obs" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=$id" ?>" method="post">
      <h3><?php echo $feedbackCount > 0 ? 'Nova Avaliação:' : 'Observações:'; ?></h3>

      <div class="status-comment">
        <img src="../../../public/icons/user.svg" alt="" />
        <span><?php echo $_SESSION["user"]["cargo"] . " " . explode(" ", $_SESSION["user"]["nome"])[0] ?>:</span>
        <textarea
          name="observacao"
          id="observacao"
          class="textarea-primary"
          oninput="autoSize(this)"
          placeholder="Digite sua observação"
          required></textarea>
      </div>

      <div class="buttons">
        <button
          class="button-primary"
          style="
              --button-color: var(--fatec-blue-500);
              --button-color-hover: var(--fatec-blue-700);
            "
          type="submit"
          name="status"
          value="Aprovada">
          Aprovar
        </button>
        <button
          class="button-primary"
          style="
              --button-color: var(--fatec-red-500);
              --button-color-hover: var(--fatec-red-400);
            "
          type="submit"
          name="status"
          value="Reprovada">
          Reprovar
        </button>
      </div>
    </form>
  </article>

  <script src="../../../components/header.js" defer></script>
  <script src="../../../scripts/form-inscricao.js" defer></script>
  <script defer>
    document.querySelectorAll("textarea[disabled]").forEach((element) => {
      element.style.height = element.scrollHeight + "px";
    });
  </script>
</body>

</html>