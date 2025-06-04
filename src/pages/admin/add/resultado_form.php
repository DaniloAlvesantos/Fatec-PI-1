<?php
session_start();
require __DIR__ . "/../../../server/controller/state.php";
require_once __DIR__ . "/../../../server/model/Chamada.php";
require_once __DIR__ . "/../../../server/model/HAE.php";

if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $chamada = $_POST['chamada'];
  $semestre = $_POST['semestre'];
  $inscricoes = $_POST['inscricoes'];

  foreach ($inscricoes as $id_inscricao => $data) {
    $quant_hae = $data['quant_hae'];
    $status = $data['status'];
    $justificativa = $data['justificativa'];

    $chamadaObj = new Chamada();
    $chamadaObj->processarResultadoChamada($id_inscricao, $quant_hae, $status, $justificativa, $chamada, $semestre);
  }
}



if (isset($_GET["id"])) {
  $id = $_GET["id"];
  $id = intval($id);
  $chamada = new Chamada();
  $hae = new HAE();

  $haeData = $hae->getHAEById($id);
  $chamadaData = $chamada->getChamadaForm($id);
  if (empty($chamadaData)) {
    header("Location: ./painel.admin.php");
    exit();
  }
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
  <link rel="stylesheet" href="../../../styles/edital.resultado.css" />
  <style>
    h3 {
      font-family: "Roboto", sans-serif;
      font-weight: 500;
      font-size: 1.3rem;
    }
  </style>
</head>

<body>
  <header-fatec
    data-button-title="Voltar"
    data-button-href="../editals.admin.php"></header-fatec>

  <section id="form-section">
    <h2 class="form-title" style="text-align: center">
      Formulario edital de aprovação
    </h2>

    <div class="table-container">
      <h3>Informações HAE</h3>
      <table class="hae-table">
        <thead>
          <tr>
            <th>
              Titulo
            </th>
            <th>
              Curso
            </th>
            <th>
              Quantidade
            </th>
            <th>
              Data Inicio
            </th>
            <th>
              Data Fim
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo $haeData->titulo ?></td>
            <td><?php echo $haeData->tip_hae ?></td>
            <td><?php echo $haeData->quant_hae ?></td>
            <td><?php echo date("d/m/Y", strtotime($haeData->data_inicio)) ?></td>
            <td><?php echo date("d/m/Y", strtotime($haeData->data_final)) ?></td>
          </tr>
        </tbody>
      </table>
    </div>

    <form onsubmit="handleSubmit(event)" id="form-subscription" method="POST">
      <span class="form-field-column">
        <label for="chamada">Selecione a chamada</label>
        <select name="chamada" id="chamada" required>
          <option value="1">1° Chamada</option>
          <option value="2">2° Chamada</option>
        </select>
      </span>

      <span class="form-field-column">
        <label for="semestre">Selecione o semestre</label>
        <select name="semestre" id="semestre" required>
          <option value="1">1° Semestre</option>
          <option value="2">2° Semestre</option>
        </select>
      </span>

      <div class="table-container" style="grid-column: 1/3">
        <h3>Docentes com inscrição aprovadas</h3>
        <table class="hae-table">
          <thead>
            <tr>
              <th>Nome do Professor</th>
              <th>Data de Envio</th>
              <th>Quantidade <br /> Solicitada</th>
              <th>Quantidade <br /> Deferidas</th>
              <th>Status</th>
              <th>Justificativa</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($chamadaData as $inscricao) {
              $nome = explode(" ", $inscricao['nomeDocente'])[0] . " " . explode(" ", $inscricao['nomeDocente'])[1];
              $id_inscricao = $inscricao['id_inscricao'];

              echo "<tr>";
              echo "<td>" . htmlspecialchars($nome) . "</td>";
              echo "<td>" . date("d/m/Y", strtotime($inscricao['dataEnvioInscricao'])) . "</td>";
              echo "<td><center>" . htmlspecialchars($inscricao['quantHAEInscricao']) . "</center></td>";

              // Important: Use array notation with id_inscricao as key
              echo "<td><center><input class='input-primary' type='number' name='inscricoes[{$id_inscricao}][quant_hae]' value='" . htmlspecialchars($inscricao['quantHAEInscricao']) . "' min='0' max='" . htmlspecialchars($inscricao['quantHAEInscricao']) . "' required></center></td>";

              echo "<td><select name='inscricoes[{$id_inscricao}][status]' required>
                              <option value=''>Selecione...</option>
                              <option value='Deferido'>Deferido</option>
                              <option value='Indeferido'>Indeferido</option>
                          </select></td>";

              echo "<td><textarea oninput='autoSize(this)' class='textarea-primary' name='inscricoes[{$id_inscricao}][justificativa]' rows='2' placeholder='Justificativa (obrigatória para indeferimento)'></textarea></td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>

      <div style="grid-column: 1/3; margin: 1rem;">
        <button
          type="submit"
          id="submitButton"
          class="button-primary"
          style="
                --button-color: var(--fatec-red-500);
                --button-color-hover: var(--fatec-red-400);
              ">
          Processar Chamada
        </button>
        <p class="error-form"></p>
      </div>
    </form>


  </section>

  <script src="../../../components/header.js" defer></script>
  <script>
    function handleSubmit(event) {
      event.preventDefault();

      const form = event.target;
      const statusSelects = form.querySelectorAll('select[name*="[status]"]');
      const justificativaTextareas = form.querySelectorAll('textarea[name*="[justificativa]"]');

      let hasErrors = false;
      const errorMessages = [];

      statusSelects.forEach((select, index) => {
        if (!select.value) {
          hasErrors = true;
          errorMessages.push(`Status deve ser selecionado para todos os docentes`);
          select.style.borderColor = 'red';
        } else {
          select.style.borderColor = '';

          if (select.value === 'Indeferido') {
            const correspondingTextarea = justificativaTextareas[index];
            if (!correspondingTextarea.value.trim()) {
              hasErrors = true;
              errorMessages.push(`Justificativa é obrigatória para indeferimento`);
              correspondingTextarea.style.borderColor = 'red';
            } else {
              correspondingTextarea.style.borderColor = '';
            }
          }
        }
      });

      const errorElement = document.querySelector('.error-form');
      if (hasErrors) {
        errorElement.textContent = errorMessages.join('. ');
        errorElement.style.color = 'red';
      } else {
        errorElement.textContent = '';
        form.submit();
      }
    }

    function autoSize(element) {
      element.style.height = "4rem";
      return (element.style.height = element.scrollHeight + "px");
    }
  </script>

</body>

</html>