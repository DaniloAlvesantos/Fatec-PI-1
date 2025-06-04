<?php
session_start();
require __DIR__ . "/../../../server/controller/state.php";
require_once __DIR__ . "/../../../server/model/Chamada.php";
require_once __DIR__ . "/../../../server/model/HAE.php";

if (!isset($_SESSION["user"])) {
  header("Location: ../../index.php");
  exit();
}

$id_hae = isset($_GET["id_hae"]) ? intval($_GET["id_hae"]) : null;
$num_chamada = isset($_GET["num_chamada"]) ? intval($_GET["num_chamada"]) : null;
$semestre = isset($_GET["semestre"]) ? $_GET["semestre"] : null;

if (!$id_hae || !$num_chamada || !$semestre) {
  header("Location: ../editals.admin.php");
  exit();
}

$chamada = new Chamada();
$hae = new HAE();

$resultados = $chamada->getDetalhesResultadoChamada($id_hae, $num_chamada, $semestre);
$haeData = $hae->getHAEById($id_hae);

if (empty($resultados) || !$haeData) {
  header("Location: ../editals.admin.php");
  exit();
}

$totalInscricoes = count($resultados);
$totalDeferidos = array_sum(array_map(fn($r) => $r['status'] === 'Deferido' ? 1 : 0, $resultados));
$totalIndeferidos = $totalInscricoes - $totalDeferidos;
$totalHorasDeferidas = array_sum(array_map(fn($r) => $r['status'] === 'Deferido' ? $r['quant_hae'] : 0, $resultados));

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Resultado do Edital">
  <title>Resultado do Edital | HAE Fatec Itapira</title>
  <link rel="stylesheet" href="../../../styles/global.css" />
  <link rel="stylesheet" href="../../../styles/components.css" />
  <link rel="stylesheet" href="../../../styles/edital.resultado.css" />
  <link rel="stylesheet" href="../../../styles/formulario.css" />
  <style>
    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin: 1rem 0;
    }

    .stat-card {
      background: var(--white);
      padding: 1rem;
      border-radius: 8px;
      border: 1px solid var(--gray-200);
      text-align: center;
    }

    .stat-number {
      font-size: 2rem;
      font-weight: bold;
      font-family: "Roboto", sans-serif;
      color: var(--fatec-red-500);
    }

    .stat-label {
      color: var(--gray-600);
      font-size: 0.9rem;
      font-family: "Roboto", sans-serif;
    }

    .status-deferido {
      color: var(--green-600);
      font-weight: 500;
    }

    .status-indeferido {
      color: var(--red-600);
      font-weight: 500;
    }

    h3 {
      font-family: "Roboto", sans-serif;
      font-weight: 500;
      font-size: 1.3rem;
    }

    @media print {
      .no-print {
        display: none !important;
      }

      body {
        font-family: 'Arial', sans-serif;
        color: black;
        background: white;
        -webkit-print-color-adjust: exact;
        /* Ensures background/text color prints accurately */
        print-color-adjust: exact;
      }

      table {
        width: 100%;
        border-collapse: collapse;
      }

      th,
      td {
        border: 1px solid #000;
        padding: 6px 8px;
        font-size: 12px;
      }

      .hae-table th {
        background-color: #eee !important;
      }
    }

    @media print {
      * {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
    }

    @page {
      size: A4 portrait;
      margin: 1cm;
    }
  </style>
</head>

<body>
  <header-fatec
    data-button-title="Voltar"
    data-button-href="../editals.admin.php" class="no-print"></header-fatec>

  <section id="form-section">
    <span class="title">
      <h1>Resultado: <?php echo $num_chamada; ?>° Chamada - <?php echo $semestre; ?>° Semestre</h1>
      <h2>Primeira Chamada</h2>
    </span>

    <div class="table-container">
      <h3>Informações HAE</h3>
      <table class="hae-table">
        <thead>
          <tr>
            <th>Titulo</th>
            <th>Curso</th>
            <th>Quantidade</th>
            <th>Data Inicio</th>
            <th>Data Fim</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo htmlspecialchars($haeData->titulo); ?></td>
            <td><?php echo htmlspecialchars($haeData->tip_hae); ?></td>
            <td><?php echo htmlspecialchars($haeData->quant_hae); ?></td>
            <td><?php echo date("d/m/Y", strtotime($haeData->data_inicio)); ?></td>
            <td><?php echo date("d/m/Y", strtotime($haeData->data_final)); ?></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="stats-container">
      <div class="stat-card">
        <div class="stat-number"><?php echo $totalInscricoes; ?></div>
        <div class="stat-label">Total de Inscrições</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" style="color: var(--green-600);"><?php echo $totalDeferidos; ?></div>
        <div class="stat-label">Deferidos</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" style="color: var(--red-600);"><?php echo $totalIndeferidos; ?></div>
        <div class="stat-label">Indeferidos</div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?php echo $totalHorasDeferidas; ?>h</div>
        <div class="stat-label">Horas Deferidas</div>
      </div>
    </div>

    <!-- Results Table -->
    <div class="table-container">
      <h3>Resultados da Chamada</h3>
      <table class="hae-table">
        <thead>
          <tr>
            <th>Nome do Professor</th>
            <th>Data de Processamento</th>
            <th>Quantidade Solicitada</th>
            <th>Quantidade Deferida</th>
            <th>Status</th>
            <th>Justificativa</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($resultados as $resultado): ?>
            <tr>
              <td><?php echo htmlspecialchars($resultado['nomeDocente']); ?></td>
              <td><?php echo date("d/m/Y H:i", strtotime($resultado['data_envio'])); ?></td>
              <td>
                <center><?php echo htmlspecialchars($resultado['quantSolicitada']); ?>h</center>
              </td>
              <td>
                <center><?php echo htmlspecialchars($resultado['quant_hae']); ?>h</center>
              </td>
              <td class="<?php echo $resultado['status'] === 'Deferido' ? 'status-deferido' : 'status-indeferido'; ?>">
                <?php echo htmlspecialchars($resultado['status']); ?>
              </td>
              <td><?php echo htmlspecialchars($resultado['justificativa'] ?: '-'); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Action Buttons -->
    <div style="display: flex; gap: 1rem; justify-content: center; margin: 2rem 0;">
      <a href="../add/resultado_form.php?id=<?php echo $id_hae; ?>">
        <button
          class="button-primary no-print"
          style="
            --button-color: var(--fatec-blue-500);
            --button-color-hover: var(--fatec-blue-700);
          ">
          Editar Resultados
        </button>
      </a>

      <button
        onclick="window.print()"
        class="button-primary no-print"
        style="
          --button-color: #cccccc;
          --button-color-hover: #dddddd;
        ">
        Imprimir
      </button>
    </div>
  </section>

  <script src="../../../components/header.js" defer></script>
</body>

</html>