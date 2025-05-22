<?php
session_start();
require __DIR__ . "/../../server/controller/state.php";
require_once __DIR__ . "/../../server/model/Inscricao.php";
require_once __DIR__ . "/../../server/model/HAE.php";

if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Inscrições enviados">
  <title>Incrições Admin | HAE Fatec Itapira</title>
  <link rel="stylesheet" href="../../styles/global.css" />
  <link rel="stylesheet" href="../../styles/components.css" />
  <link rel="stylesheet" href="../../styles/haes.css" />
</head>

<body>
  <header-fatec
    data-button-title="Voltar"
    data-button-href="./painel.admin.php"></header-fatec>

  <section>
    <h1>Inscrições</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="get">
      <label for="search" class="search-container">
        Filtro
      </label>
      <select name="filtro" onchange="this.form.submit()">
        <option value="" disabled selected>Selecionar</option>
        <option value="all">Todos</option>
        <option value="DSM">DSM</option>
        <option value="GPI">GPI</option>
        <option value="GTI">GTI</option>
        <option value="GE">GE</option>
      </select>
    </form>

    <main>
      <?php
      $inscricao = new Inscricao();
      $all_inscricoes = isset($_GET["filtro"]) && $_GET["filtro"] !== "all" ? $inscricao->getMySubscriptionsByTipHae($_GET["filtro"]) : $inscricao->adminGetAllSubscriptions($_SESSION["user"]['cargo'], $_SESSION["user"]['curso']);
      if (count($all_inscricoes) == 0 || !isset($all_inscricoes)) {
        echo "<h2 class='no-hae'>Nenhuma inscrição encontrada &#128533;</h2>";
      }

      foreach ($all_inscricoes as $inscricao) {
        $hae = new HAE();
        $hae = $hae->getHAEById($inscricao['id_hae']);
        $data_envio = date('d/m/y', strtotime($inscricao['data_envio']));


        echo "
          <div class='card-hae card-primary'>
              <span class='card-hae-title'>
                <p class='card-hae-tag'>H.A.E</p>
                <h3 class=''>{$hae->titulo}</h3>
              </span>

              <span class='card-hae-info'>
                <p class='card-hae-desc'>{$hae->descricao}</p>
                <p>Quantidade HAE: {$inscricao['quant_hae']}</p>
                <p>Curso: {$hae->tip_hae}</p>
                <p>Status: <strong>{$inscricao['status']}</strong></p>
              </span>

              <span class='card-hae-date'>
                <img src='../../public/icons/calendar-clock.svg' alt='calendario' />
                <p>{$data_envio}</p>
              </span>

              <a href='./preview/inscricao.admin.php?id={$inscricao['id_inscricao']}'>
                <button
                  class='button-primary'
                  style='
                      --button-color: var(--fatec-red-500);
                      --button-color-hover: var(--fatec-red-400);
                    '>
                  Visualizar
                </button>
              </a>
            </div>";
      }
      ?>
    </main>
  </section>

  <script src="../../components/header.js" defer></script>
</body>

</html>