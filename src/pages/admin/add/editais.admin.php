<?php
session_start();
require __DIR__ . "/../../../server/controller/state.php";
require_once __DIR__ . "/../../../server/model/Chamada.php";

if (!isset($_SESSION["user"])) {
    header("Location: ../index.php");
    exit();
}

$chamada = new Chamada();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Formulário Edital">
    <title>Editais | HAE Fatec Itapira</title>
    <link rel="stylesheet" href="../../../styles/global.css" />
    <link rel="stylesheet" href="../../../styles/components.css" />
    <link rel="stylesheet" href="../../../styles/edital.css" />
    <style>
        .form-title {
            font-family: "Roboto", sans-serif;
            font-size: var(--text-2xl-font-size);
            line-height: var(--text-xl-line-height);
            margin-bottom: 2rem;
        }

        #form-section {
            display: flex;
            flex-direction: column;
            padding: 2rem;
            gap: 1rem;
        }
    </style>
</head>

<body>
    <header-fatec
        data-button-title="Voltar"
        data-button-href="../editals.admin.php"></header-fatec>

    <section id="form-section">
        <h2 class="form-title" style="text-align: center">
            Chamadas de Inscrições pendentes
        </h2>
        <?php
        $chamadas = $chamada->getPendenteChamadas();

        if (count($chamadas) > 0) {
            foreach ($chamadas as $chamada) {
                echo '
            <div class="card-secondary">
        <span>
          <h4>' . $chamada["titulo"] . '</h4>
          <h5>Curso: ' . $chamada["tip_hae"] . '</h5>
          <p>
            <img
              src="../../../public/icons/calendar-clock.svg"
              alt="calendar clock"
            />' . date("d/m/Y", strtotime($chamada["data_final"])) . '
          </p>
        </span>
        <a href="./resultado_form.php?id=' . $chamada["id_hae"] . '">
          <button
            class="button-primary"
            style="
              --button-color: var(--fatec-red-500);
              --button-color-hover: var(--fatec-red-400);
            "
          >Realizar chamada</button>
        </a>
      </div>';
            }
        } else {
            echo '<p>Nenhuma chamada pendente encontrada.</p>';
        }
        ?>
    </section>

    <script src="../../../components/header.js" defer></script>
</body>

</html>