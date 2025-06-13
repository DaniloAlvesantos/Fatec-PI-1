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
  <meta name="description" content="Realtoórios enviados">
  <title>Relatórios | HAE Fatec Itapira</title>
  <link rel="stylesheet" href="../../styles/global.css" />
  <link rel="stylesheet" href="../../styles/components.css" />
  <link rel="stylesheet" href="../../styles/relatorios.css" />
  <style>
    main {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(440px, 1fr));
      justify-items: center;
      gap: 1rem;
    }
  </style>
</head>

<body>
  <header-fatec
    data-button-title="Voltar"
    data-button-href="./painel.admin.php"></header-fatec>

  <section>
    <h2>Enviados</h2>
    <hr />
    <main><?php
          $relatorios = $relatorio->adminGetAllRelatorios($_SESSION["user"]["cargo"], $_SESSION["user"]["curso"]);

          if (empty($relatorios)) {
            echo '<p class="no-relatorio">Nenhum relatório enviado.</p>';
          } else {
            foreach ($relatorios as $rel) {
              echo '
              <div class="card-container">
                <div class="card-primary card-rel">
                  <span class="card-rel-title">
                    <h3 class="">' . htmlspecialchars($rel['projetoTitulo']) . '</h3>
                  </span>
                  <span class="card-rel-info">
                    <p>HAE: ' . htmlspecialchars($rel['haeTitulo']) . '</p>
                    <p>Curso: ' . htmlspecialchars($rel['tip_hae']) . '</p>
                    <p>Docente: ' . htmlspecialchars($rel['docenteNome']) . '</p>
                  </span>
                  <span class="card-rel-date">
                    <img src="../../public/icons/calendar-clock.svg" alt="calendario" />
                    <p>' . date("d/m", strtotime($rel['data_entrega'])) . '</p>
                  </span>
                  <a href="./preview/relatorio.admin.php?id=' . $rel['id_inscricao'] . '">
                    <button
                      class="button-primary"
                      style="
                        --button-color: var(--fatec-red-500);
                        --button-color-hover: var(--fatec-red-400);
                      "
                    >
                      Visualizar
                    </button>
                  </a>
                </div>
              </div>';
            }
          }
          ?></main>
  </section>

  <script src="../../components/header.js" defer></script>
</body>

</html>