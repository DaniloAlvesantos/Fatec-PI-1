<?php
session_start();
require __DIR__ . "/../server/controller/state.php";
require_once __DIR__ . "/../server/model/HAE.php";
require_once __DIR__ . "/../server/model/Docente.php";


if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  exit();
}

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // echo "<h2>POST Data:</h2>";
  // echo "<pre>" . print_r($_POST, true) . "</pre>";

  // // For AJAX/JSON submissions add:
  // $json_input = file_get_contents('php://input');
  // echo "<h2>Raw JSON Input:</h2>";
  // echo "<pre>" . htmlspecialchars($json_input) . "</pre>";
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
          <p><?php echo date("d/m", strtotime($hae->data_inicio)); ?> - <?php echo date("d/m", strtotime($hae->data_inicio)); ?></p>
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
    <form onsubmit="handleSubmit(event)" id="form-subscription" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
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