<?php
session_start();
require __DIR__ . "/../../../server/controller/state.php";
require_once __DIR__ . "/../../../server/model/HAE.php";

if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
  exit();
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $id = intval($id);
  $hae = new HAE();
  $hae = $hae->getHAEById($id);

  if (!$hae) {
    header("Location: ../haes.admin.php");
    exit();
  }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="HAE Admin">
  <title>HAE Admin | HAE Fatec Itapira</title>
  <link rel="stylesheet" href="../../../styles/components.css" />
  <link rel="stylesheet" href="../../../styles/formulario.css" />
  <link rel="stylesheet" href="../../../styles/global.css" />
</head>

<body>
  <header-fatec data-button-title="Voltar" data-button-href="../haes.admin.php"></header-fatec>

  <section id="hae-section">
    <div id="hae-container">
      <div>
        <h1><?php echo $hae->titulo; ?></h1>
        <p>Curso: <?php echo $hae->tip_hae; ?></p>
      </div>
      <div class="hae-container-header">
        <span class="hae-container-header-info">
          <img src="../../../public/icons/hourglass.svg" alt="" />
          <p>Quantidade HAE: <?php echo $hae->quant_hae; ?></p>
        </span>
        <span class="hae-container-header-info">
          <img src="../../../public/icons/calendar-clock.svg" alt="" />
          <p><?php echo date('d/m', strtotime($hae->data_inicio)); ?> - <?php echo date("d/m", strtotime($hae->data_final)); ?></p>
        </span>
      </div>
    </div>

    <div id="about-container">
      <h3>Sobre a vaga</h3>
      <p><?php echo $hae->descricao; ?></p>
    </div>
  </section>
  <hr />

  <script src="../../../scripts/form-inscricao.js"></script>
  <script src="../../../components/header.js"></script>
</body>

</html>