<?php
session_start();
require_once __DIR__ . "/../server/model/HAE.php";
require_once __DIR__ . "/../server/model/Docente.php";
require_once __DIR__ . "/../server/model/Inscricao.php";
require_once __DIR__ . "/../server/model/Projeto.php";

// Handle AJAX form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

  if ($isAjax) {
    if (!isset($_SESSION["user"])) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'User session expired']);
      exit();
    }

    $docente = new Docente();
    $docente = $docente->fromArray($_SESSION["user"]);

    try {
      $outrasHaes = isset($_POST['outras-fatecs']) ? true : false;
      $quantidadeHaes = isset($_POST['quantidade-haes']) ? intval($_POST['quantidade-haes']) : 0;
      $tituloProjeto = isset($_POST['titulo-projeto']) ? $_POST['titulo-projeto'] : '';
      $dataInicio = isset($_POST['data-inicio']) ? $_POST['data-inicio'] : '';
      $dataFinalizacao = isset($_POST['data-finalizacao']) ? $_POST['data-finalizacao'] : '';
      $diasExecucao = isset($_POST['dias_execucao']) ? json_encode(json_decode($_POST['dias_execucao'])) : json_encode([]);
      $metas = isset($_POST['metas']) ? $_POST['metas'] : '';
      $objetivos = isset($_POST['objetivos']) ? $_POST['objetivos'] : '';
      $justificativa = isset($_POST['justificativa']) ? $_POST['justificativa'] : '';
      $recursos = isset($_POST['recursos']) ? $_POST['recursos'] : '';
      $resultadoEsperado = isset($_POST['resultado-esperado']) ? $_POST['resultado-esperado'] : '';
      $metodologia = isset($_POST['metodologia']) ? $_POST['metodologia'] : '';
      $cronograma = isset($_POST['cronograma']) ? $_POST['cronograma'] : '';

      // Get HAE from GET parameter
      $id = $_GET['id'] ?? 0;
      $id = intval($id);
      $hae = new HAE();
      $hae = $hae->getHAEById($id);

      if (!$hae) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'HAE not found']);
        exit();
      }

      $projeto = new Projeto();
      $inscricao = new Inscricao();

      // Create the project
      $projetoId = $projeto->createProjeto($tituloProjeto, $dataInicio, $dataFinalizacao, $hae->getIdHAE(), json_encode([
        'metas' => $metas,
        'objetivos' => $objetivos,
        'justificativa' => $justificativa,
        'recursos' => $recursos,
        'resultado_esperado' => $resultadoEsperado,
        'metodologia' => $metodologia,
        'cronograma' => $cronograma
      ]), $diasExecucao);

      if (!$projetoId) {
        throw new Exception('Failed to create project');
      }

      // Create the subscription
      $inscricaoId = $inscricao->createSubscription(
        $docente->getIdDocente(),
        $hae->getIdHAE(),
        $projetoId,
        date("Y-m-d H:i:s"),
        $quantidadeHaes,
        $outrasHaes ? 1 : 0
      );

      if ($inscricaoId) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Inscrição realizada com sucesso']);
        exit();
      } else {
        throw new Exception('Failed to create subscription');
      }
    } catch (Exception $e) {
      header('Content-Type: application/json');
      http_response_code(500);
      echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
      exit();
    }
    exit(); // Make sure to exit after handling AJAX
  }
}

if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  exit();
}

require __DIR__ . "/../server/controller/state.php";

$docente = new Docente();
$docente = $docente->fromArray($_SESSION["user"]);

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $id = intval($id);
  $hae = new HAE();
  $hae = $hae->getHAEById($id);

  if (!$hae) {
    header("Location: ./haes.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Preencha o formulário da HAE">
  <title>Formulario HAE | HAE Fatec Itapira</title>
  <link rel="stylesheet" href="../styles/components.css" />
  <link rel="stylesheet" href="../styles/formulario.css" />
  <link rel="stylesheet" href="../styles/global.css" />
</head>

<body>
  <header-fatec data-button-title="Voltar" data-button-href="./haes.php"></header-fatec>

  <section id="hae-section">
    <div id="hae-container">
      <div>
        <h1><?php echo $hae->titulo; ?></h1>
        <p>Curso: <?php echo $hae->tip_hae; ?></p>
      </div>
      <div class="hae-container-header">
        <span class="hae-container-header-info">
          <img src="../public/icons/hourglass.svg" alt="" />
          <p>Quantidade HAE: <?php echo $hae->quant_hae; ?></p>
        </span>
        <span class="hae-container-header-info">
          <img src="../public/icons/calendar-clock.svg" alt="" />
          <p><?php echo date("d/m", strtotime($hae->data_inicio)); ?> - <?php echo date("d/m", strtotime($hae->data_final)); ?></p>
        </span>
      </div>
    </div>

    <div id="about-container">
      <h3>Sobre a vaga</h3>
      <p><?php echo $hae->descricao; ?></p>
    </div>
  </section>
  <hr />
  <section id="form-section">
    <h2 class="form-title">Formulario de inscrição</h2>
    <form onsubmit="handleSubmit(event)" id="form-subscription" method="post">
      <div class="form-container">
        <span class="checkfield">
          <input type="checkbox" id="outras-fatecs" name="outras-fatecs" <?php echo $docente->outras_fatecs == true ? htmlspecialchars("checked") : "" ?> />
          <label for="outras-fatecs">Possui aulas em outras Fatecs?</label>
        </span>

        <span class="form-field-line">
          <input
            class="input-primary"
            type="number"
            id="quantidade-haes"
            name="quantidade-haes"
            max=<?php echo htmlspecialchars($hae->quant_hae) ?>
            placeholder="0" />
          <label for="quantidade-haes">Quantidade de HAEs</label>
        </span>

        <span class="form-field-column">
          <label for="titulo-projeto">Título do Projeto</label>
          <input
            class="input-primary"
            type="text"
            id="titulo-projeto"
            name="titulo-projeto"
            placeholder="Nome do seu projeto" />
        </span>

        <span class="form-field-column">
          <label for="data-inicio">Data de Início</label>
          <input
            class="input-primary"
            type="date"
            id="data-inicio"
            name="data-inicio"
            placeholder="Data Início" />
        </span>

        <span class="form-field-column"><label for="data-finalizacao">Data de Finalização</label>
          <input
            class="input-primary"
            type="date"
            id="data-finalizacao"
            name="data-finalizacao"
            placeholder="Data Final" /></span>

        <span class="form-field-column"><label>Dias de Execução</label>
          <p class="error"></p>
          <div id="container-inputs" class="form-field-column">
            <input
              class="input-primary"
              type="text"
              id="dia-execucao1"
              name="dia-execucao1"
              autocomplete="off"
              placeholder="Segunda, Noite, 17-19"
              ondblclick="removeInput(this)"
              onblur="verifyExecDay(this)" />
          </div>
          <button
            class="button-primary"
            style="
                --button-color: var(--fatec-red-400);
                --button-color-hover: var(--fatec-red-500);
                padding: 0.5rem 1.5rem;
              "
            type="button"
            id="addInputButton"
            onclick="addInput()">
            +
          </button>
          <p class="warn">Duplo click no campo para pagar</p>
        </span>

        <span class="form-field-column">
          <label for="metas">Metas</label>
          <textarea
            oninput="autoSize(this)"
            class="textarea-primary"
            type="text"
            id="metas"
            name="metas"
            placeholder="Diga suas metas"></textarea>
        </span>
      </div>

      <div class="form-container">
        <span class="form-field-column"><label for="objetivos">Objetivos</label>
          <textarea
            oninput="autoSize(this)"
            class="textarea-primary"
            type="text"
            id="objetivos"
            name="objetivos"
            placeholder="Diga seus objetivos"></textarea>
        </span>

        <span class="form-field-column"><label for="justificativa">Justificativa</label>
          <textarea
            oninput="autoSize(this)"
            class="textarea-primary"
            type="text"
            id="justificativa"
            name="justificativa"
            placeholder="Justifique seu projeto"></textarea>
        </span>

        <span class="form-field-column">
          <label for="recursos">Recursos, Materiais e Humanos</label>
          <input
            class="input-primary"
            type="text"
            id="recursos"
            name="recursos"
            placeholder="Sala, Mesa, Caderno..." />
        </span>

        <span class="form-field-column">
          <label for="resultado-esperado">Resultado Esperado</label>
          <textarea
            oninput="autoSize(this)"
            class="textarea-primary"
            type="text"
            id="resultado-esperado"
            name="resultado-esperado"
            placeholder="Expectativas de resultado"></textarea>
        </span>

        <span class="form-field-column">
          <label for="metodologia">Metodologia</label>
          <textarea
            oninput="autoSize(this)"
            class="textarea-primary"
            type="text"
            id="metodologia"
            name="metodologia"
            placeholder="Medoto de realização"></textarea>
        </span>

        <span class="form-field-column">
          <label for="cronograma">Cronograma das Atividades</label>
          <textarea
            oninput="autoSize(this)"
            class="textarea-primary"
            id="cronograma"
            name="cronograma"
            placeholder="Agosto: Apresentação..."></textarea>
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
      <p class="error-form"></p>
    </form>
  </section>

  <script src="../scripts/form-inscricao.js" defer></script>
  <script src="../components/header.js" defer></script>
</body>

</html>