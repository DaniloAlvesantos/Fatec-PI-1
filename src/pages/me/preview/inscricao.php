<?php
session_start();
require_once __DIR__ . "/../../../server/model/HAE.php";
require_once __DIR__ . "/../../../server/model/Inscricao.php";
require_once __DIR__ . "/../../../server/model/Projeto.php";


if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  exit();
}

require __DIR__ . "/../../../server/controller/state.php";

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $id = intval($id);

  $inscricao = new Inscricao();
  $projeto = new Projeto();
  $hae = new HAE();

  $inscricao = $inscricao->getMySubscriptionsById($id);
  $hae = $hae->getHAEById($inscricao->getIdHae());
  $projeto = $projeto->getProjetoById($inscricao->getIdProjeto());
  $descricoes = json_decode($projeto->descricoes,true);

  if (!$inscricao || !isset($_GET["id"])) {
    header("Location: ../inscricoes.php");
    exit();
  }
} else if (!isset($_GET['id'])) {
  header("Location: ../inscricoes.php");
  exit();
}


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Sua inscrição" />
  <title>Inscrição | HAE Fatec Itapira</title>
  <link rel="stylesheet" href="../../../styles/global.css" />
  <link rel="stylesheet" href="../../../styles/components.css" />
  <link rel="stylesheet" href="../../../styles/formulario.css" />
</head>

<body>
  <header-fatec
    data-button-title="Voltar"
    data-button-href="../inscricoes.php"></header-fatec>
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
    <h2 class="form-title">Formulario de inscrição</h2>
    <form id="form-subscription">
      <div class="form-container">
        <span class="checkfield">
          <input
            type="checkbox"
            id="outras-fatecs"
            name="outras-fatecs"
            <?php echo $_SESSION["user"]["outras_fatecs"] ? htmlspecialchars("checked") : null; ?>
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
            disabled><?php echo $descricoes["metas"];?>
          </textarea>
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
            disabled><?php echo $descricoes["objetivos"];?></textarea>
        </span>

        <span class="form-field-column"><label for="justificativa">Justificativa</label>
          <textarea
            class="textarea-primary"
            type="text"
            id="justificativa"
            name="justificativa"
            placeholder="Justifique seu projeto"
            disabled><?php echo $descricoes["justificativa"];?></textarea>
        </span>

        <span class="form-field-column">
          <label for="recursos">Recursos, Materiais e Humanos</label>
          <input
            class="input-primary"
            type="text"
            id="recursos"
            name="recursos"
            placeholder="Sala, Mesa, Caderno..."
            value="<?php echo $descricoes["recursos"];?>"
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
            disabled><?php echo $descricoes["resultado_esperado"];?></textarea>
        </span>

        <span class="form-field-column">
          <label for="metodologia">Metodologia</label>
          <textarea
            class="textarea-primary"
            type="text"
            id="metodologia"
            name="metodologia"
            placeholder="Medoto de realização"
            disabled><?php echo $descricoes["metodologia"];?></textarea>
        </span>

        <span class="form-field-column">
          <label for="cronograma">Cronograma das Atividades</label>
          <textarea
            class="textarea-primary"
            id="cronograma"
            name="cronograma"
            placeholder="Agosto: Apresentação..."
            disabled><?php echo $descricoes["cronograma"];?></textarea>
        </span>
      </div>
    </form>
  </section>

  <hr />

  <article class="status-container">
    <span class="status-header">
      <h2>Status: Indeferido &#128533;</h2>
      <p>Desta vez, você não conseguiu. Desta vez, tá!?</p>
    </span>

    <main>
      <h3>Observações:</h3>

      <div class="status-comment">
        <img src="../../../public/icons/user.svg" alt="" />
        <span>Coordenadora Marcia:</span>
        <p>
          Quantidade de HAEs solicitadas não encaixam com à quantidade de
          horas de aulas.
        </p>
      </div>
    </main>
  </article>

  <script src="../../../components/header.js" defer></script>
  <script defer>
    document.querySelectorAll("textarea").forEach((element) => {
      element.style.height = element.scrollHeight + "px";
    });
  </script>
</body>

</html>