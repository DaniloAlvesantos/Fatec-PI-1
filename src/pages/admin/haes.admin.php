<?php
session_start();
require __DIR__ . "/../../server/controller/state.php";
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
  <meta name="description" content="HAEs Admin">
  <title>HAES Admin | HAE Fatec Itapira</title>
  <link rel="stylesheet" href="../../styles/global.css" />
  <link rel="stylesheet" href="../../styles/components.css" />
  <link rel="stylesheet" href="../../styles/haes.css" />
</head>

<body>
  <header-fatec
    data-button-title="Home"
    data-button-href="./painel.admin.php"></header-fatec>
  <section>
    <h1>HAEs Cadastradas</h1>
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
      $haes = new HAE();
      $all_haes = isset($_GET["filtro"]) && $_GET["filtro"] !== "all" ? $haes->getHAEByTip($_GET["filtro"]) : $haes->getHaes();
      if (count($all_haes) == 0 || !isset($all_haes)) {
        echo "<h2 class='no-hae'>Nenhum HAE encontrado &#128533;
</h2>";
      }
      foreach ($all_haes as $hae) {
        $data_inicio = date('d/m', strtotime($hae['data_inicio']));
        $data_final = date('d/m', strtotime($hae['data_final']));

        echo "
          <div class='card-hae card-primary'>
              <span class='card-hae-title'>
                  <p class='card-hae-tag'>H.A.E</p>
                  <h3 class=''>Fatec Itapira</h3>
              </span>
      
              <span class='card-hae-info'>
                  <p>{$hae['titulo']}</p>
                  <p>Quantidade HAE: {$hae['quant_hae']}</p>
                  <p>Curso: {$hae['tip_hae']}</p>
              </span>
      
              <span class='card-hae-date'>
                  <img src='../../public/icons/calendar-clock.svg' alt='calendario' />
                  <p>{$data_inicio} - {$data_final}</p>
              </span>
      
              <a href='./preview/hae.admin.php?id={$hae['id_hae']}'>
                  <button
                      class='button-primary'
                      style='
                          --button-color: var(--fatec-red-500);
                          --button-color-hover: var(--fatec-red-400);
                      '
                  >
                      Visualizar
                  </button>
              </a>
          </div>
          ";
      }
      ?>
    </main>
  </section>

  <a href="./add/hae_form.admin.php">
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