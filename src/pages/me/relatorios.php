<?php
session_start();
require __DIR__ . "/../../server/controller/state.php";
require_once __DIR__ . "/../../server/model/Relatorio.php";

if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  exit();
}

$relatorio = new Relatorio();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Confira os relatórios">
  <title>Relatorios</title>
  <link rel="stylesheet" href="../../styles/global.css" />
  <link rel="stylesheet" href="../../styles/components.css" />
  <link rel="stylesheet" href="../../styles/relatorios.css" />
</head>

<body>
  <header-fatec
    data-button-title="Voltar"
    data-button-href="../home.php"></header-fatec>

  <section>
    <?php
    $relatorios = $relatorio->getRelatorios($_SESSION["user"]["id_docente"]);
    if (empty($relatorios)) {
      echo '<p class="no-relatorio">Nenhum relatório pendente.</p>';
    } else {
      echo "<h2>Pendentes</h2>
            <hr />";
      foreach ($relatorios as $rel) {
        echo '
              <div class="card-container">
                <div class="card-primary card-rel">
                  <span class="card-rel-title">
                    <h3 class="">' . htmlspecialchars($rel['haeTitulo']) . '</h3>
                  </span>
                  <span class="card-rel-info">
                    <p>Projeto: ' . htmlspecialchars($rel['titulo']) . '</p>
                    <p>Curso: ' . htmlspecialchars($rel['tip_hae']) . '</p>
                  </span>
                  <span class="card-rel-date">
                    <img src="../../public/icons/calendar-clock.svg" alt="calendario" />
                    <p>' . date("d/m", strtotime($rel['data_final'])) . '</p>
                  </span>
                  <a href="./relatorio_formulario.php?id=' . $rel['id_inscricao'] . '">
                    <button
                      class="button-primary"
                      style="
                        --button-color: var(--fatec-red-500);
                        --button-color-hover: var(--fatec-red-400);
                      "
                    >
                      Realizar
                    </button>
                  </a>
                </div>
              </div>';
      }
    }
    ?>

    <?php
    $relatoriosEnviados = $relatorio->getRelatoriosEnviados($_SESSION["user"]["id_docente"]);
    if (count($relatoriosEnviados) > 0) {
      echo "<h2>Enviados</h2>
            <hr />
        ";
      foreach ($relatoriosEnviados as $rel) {
        echo '
              <div class="card-container">
                <div class="card-primary card-rel">
                  <span class="card-rel-title">
                    <h3 class="">' . htmlspecialchars($rel['projetoTitulo']) . '</h3>
                  </span>
                  <span class="card-rel-info">
                    <p>HAE: ' . htmlspecialchars($rel['haeTitulo']) . '</p>
                    <p>Curso: ' . htmlspecialchars($rel['tip_hae']) . '</p>
                  </span>
                  <span class="card-rel-date">
                    <img src="../../public/icons/calendar-clock.svg" alt="calendario" />
                    <p>' . date("d/m", strtotime($rel['data_entrega'])) . '</p>
                  </span>
                  <a href="./preview/relatorio.php?id=' . $rel['id_inscricao'] . '">
                    <button
                      class="button-primary"
                      style="
                        --button-color: var(--fatec-blue-500);
                        --button-color-hover: var(--fatec-blue-700);
                      "
                    >
                      Visualizar
                    </button>
                  </a>
                </div>
              </div>';
      }
    }
    ?>
  </section>

  <script src="../../components/header.js" defer></script>
</body>

</html>