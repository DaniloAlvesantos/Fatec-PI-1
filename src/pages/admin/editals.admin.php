<?php
session_start();
require __DIR__ . "/../../server/controller/state.php";
require_once __DIR__ . "/../../server/model/Chamada.php";
require_once __DIR__ . "/../../server/model/HAE.php";

if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  exit();
}

// Get all chamadas with HAE information
$chamada = new Chamada();
$hae = new HAE();

// Get all chamadas grouped by HAE, chamada number and semester
$chamadasData = $chamada->getAllChamadasGrouped();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Edital Admin">
  <title>Edital Admin| HAE Fatec Itapira</title>
  <link rel="stylesheet" href="../../styles/global.css" />
  <link rel="stylesheet" href="../../styles/components.css" />
  <link rel="stylesheet" href="../../styles/edital.css" />
  <style>
    .no-chamadas {
      text-align: center;
      padding: 2rem;
      color: var(--gray-600);
    }

    .chamada-stats {
      display: flex;
      gap: 1rem;
      margin-top: 0.5rem;
      font-size: 0.9rem;
    }

    .stat-item {
      display: flex;
      align-items: center;
      gap: 0.25rem;
      color: var(--gray-600);
    }

    .stat-deferido {
      color: var(--green-600);
    }

    .stat-indeferido {
      color: var(--red-600);
    }
  </style>
</head>

<body>
  <header-fatec
    data-button-title="Voltar"
    data-button-href="./painel.admin.php"></header-fatec>

  <h1>Resultados das inscrições</h1>

  <section>
    <?php if (empty($chamadasData)): ?>
      <div class="no-chamadas">
        <p>Nenhuma chamada foi processada ainda.</p>
      </div>
    <?php else: ?>
      <?php foreach ($chamadasData as $chamadaInfo): ?>
        <div class="card-secondary">
          <span>
            <h4><?php echo htmlspecialchars($chamadaInfo['titulo_hae']); ?></h4>
            <h5>
              <?php echo $chamadaInfo['num_chamada']; ?>° Chamada -
              <?php echo $chamadaInfo['semestre']; ?>° Semestre de <?php echo date('Y'); ?>
            </h5>
            <p>
              <img
                src="../../public/icons/calendar-clock.svg"
                alt="calendar clock" />
              <?php echo date("d/m/Y", strtotime($chamadaInfo['data_envio'])); ?>
            </p>

            <div class="chamada-stats">
              <span class="stat-item stat-deferido">
                <strong><?php echo $chamadaInfo['total_deferidos']; ?></strong> Deferidos
              </span>
              <span class="stat-item stat-indeferido">
                <strong><?php echo $chamadaInfo['total_indeferidos']; ?></strong> Indeferidos
              </span>
              <span class="stat-item">
                <strong><?php echo $chamadaInfo['total_inscricoes']; ?></strong> Total
              </span>
            </div>
          </span>
          <a href="./preview/edital.resultado.admin.php?id_hae=<?php echo $chamadaInfo['id_hae']; ?>&num_chamada=<?php echo $chamadaInfo['num_chamada']; ?>&semestre=<?php echo $chamadaInfo['semestre']; ?>">
            <button
              class="button-primary"
              style="
                  --button-color: var(--fatec-red-500);
                  --button-color-hover: var(--fatec-red-400);
                ">
              Conferir
            </button>
          </a>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>

  <a href="./add/editais.admin.php">
    <button
      class="add-button-primary"
      style="
          --add-button-color: var(--fatec-red-500);
          --add-button-color-hover: var(--fatec-red-400);
        ">
      <img src="../../public/icons/plus.svg" alt="plus" />
    </button>
  </a>

  <script src="../../components/header.js" defer></script>
</body>

</html>