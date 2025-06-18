<?php
session_start();
require_once __DIR__ . "/../../../server/controller/state.php";
require_once __DIR__ . "/../../../server/model/Relatorio.php";
require_once __DIR__ . "/../../../server/model/HAE.php";
require_once __DIR__ . "/../../../server/model/Projeto.php";
require_once __DIR__ . "/../../../server/model/Feedback.php";

if (!isset($_SESSION["user"])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status']) && isset($_POST['observacao'])) {
    if (!isset($_GET['id'])) {
        header("Location: ../relatorios.admin.php");
        exit();
    }
    $relatorio = new Relatorio();

    $id_relatorio = $relatorio->getIdRelatorioByInscricao(intval($_GET['id']));
    $resultado = $_POST['status'];
    $comentario_text = trim($_POST['observacao']);
    $cargo = $_SESSION["user"]["cargo"];
    $id_docente = $_SESSION["user"]["id_docente"];

    if (empty($comentario_text)) {
        $error_message = "O comentário é obrigatório.";
    } else {

        if ($relatorio->createRelatorioFeedback($id_relatorio, $resultado, $cargo, $id_docente, $comentario_text)) {
            $success_message = "Feedback registrado com sucesso!";
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id_relatorio . "&success=1");
            exit();
        } else {
            $error_message = "Erro ao registrar o feedback. Tente novamente.";
        }
    }
}

$success_message = isset($_GET['success']) ? "Feedback registrado com sucesso!" : null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = intval($id);
    $relatorio = new Relatorio();
    $hae = new HAE();
    $projeto = new Projeto();
    $feedback = new Feedback();

    $relatorioData = $relatorio->getRelatorioWithFeedback($id);
    if (!$relatorioData) {
        header("Location: ../relatorios.admin.php");
        exit();
    }

    $hae = $hae->getHAEById($relatorioData['id_hae']);
    $projeto = $projeto->getProjetoById($relatorioData['id_projeto']);
    $descricoes = json_decode($projeto->descricoes, true);
    $relatorioDescricoes = json_decode($relatorioData["descricoes"], true);

    $referer = $_SERVER["HTTP_REFERER"] ?? $_SERVER['REQUEST_URI'];
    $marker = '/src/';
    $pos = strpos($referer, $marker);
    $pdf_url_path = substr($referer, 0, $pos + strlen($marker)) . "server/assets/uploads/relatorios/" . $relatorioData["docenteId"] . "/" . $relatorioData["pdf_nome"];

    $allFeedbacks = $relatorio->getAllFeedbacksByRelatorio($id);
    $feedbackCount = count($allFeedbacks);

    $hasAnyFeedback = $relatorio->hasAnyFeedback($id);
} else {
    header("Location: ../relatorios.admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Relatório enviados">
    <title>Relatório Admin | HAE Fatec Itapira</title>
    <link rel="stylesheet" href="../../../styles/components.css" />
    <link rel="stylesheet" href="../../../styles/formulario.css" />
    <link rel="stylesheet" href="../../../styles/global.css" />
    <style>
        .form-field-column p {
            font-family: "Roboto", sans-serif;
            font-weight: 500;
            margin: 0.2rem 1rem;
        }

        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-weight: bold;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            font-family: "Roboto", sans-serif;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            font-family: "Roboto", sans-serif;
        }
    </style>
</head>

<body>
    <header-fatec
        data-button-title="Voltar"
        data-button-href="../relatorios.admin.php"></header-fatec>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <section id="hae-section">
        <div id="hae-container">
            <div>
                <h1><?php echo htmlspecialchars($relatorioData["haeTitulo"]); ?></h1>
                <p>Curso: <?php echo htmlspecialchars($relatorioData["tip_hae"]); ?></p>
            </div>
            <span class="hae-container-header-info">
                <img
                    src="../../../public/icons/calendar-clock.svg"
                    alt="Ícone de calendário com relógio" />
                <p><?php echo date("d/m", strtotime($relatorioData["data_inicio"])) . " - " . date("d/m", strtotime($relatorioData["data_final"])); ?></p>
            </span>
        </div>
        <div id="about-container">
            <h3>Info:</h3>
            <ul>
                <li>
                    <strong>Resultado esperado:</strong> <?php echo htmlspecialchars($descricoes["resultado_esperado"]); ?>
                </li>
                <li>
                    <strong>Metodologia:</strong> <?php echo htmlspecialchars($descricoes["metodologia"]); ?>
                </li>
                <li>
                    <strong>Cronograma:</strong>
                    <?php echo htmlspecialchars($descricoes["cronograma"]); ?>
                </li>
            </ul>
        </div>
    </section>
    <hr />
    <section id="form-section">
        <h2 class="form-title">Visualização relatório</h2>
        <form onsubmit="handleSubmit(event)" id="form-subscription">
            <div class="form-container">
                <span class="form-field-column">
                    <label for="aprov">Aproveitamento</label>
                    <textarea
                        oninput="autoSize(this)"
                        class="textarea-primary"
                        id="aprov"
                        name="aprov"
                        placeholder="Diga o Aproveitamento do projeto"
                        disabled><?php echo htmlspecialchars($relatorioDescricoes["aproveitamento"]); ?></textarea>
                </span>
                <span class="form-field-column">
                    <label for="resultado">Resultados Atingidos</label>
                    <textarea
                        oninput="autoSize(this)"
                        class="textarea-primary"
                        id="resultado"
                        name="resultado"
                        placeholder="Diga os resultados atingidos"
                        disabled><?php echo htmlspecialchars($relatorioDescricoes["resultados"]); ?></textarea>
                </span>
            </div>
            <div class="form-container">
                <span class="form-field-column">
                    <label for="anotacoes">Anotações</label>
                    <textarea
                        oninput="autoSize(this)"
                        class="textarea-primary"
                        id="anotacoes"
                        name="anotacoes"
                        placeholder="Suas anotações"
                        disabled><?php echo htmlspecialchars($relatorioDescricoes["anotacoes"]); ?></textarea>
                </span>
                <span class="form-field-column" id="fileField">
                    <p>Documento PDF</p>
                    <label
                        for="documento"
                        class="input-arq"
                        id="file-container"
                        onclick="handleDownload('<?php echo htmlspecialchars($pdf_url_path); ?>')">
                        <img src="../../../public/icons/file.svg" alt="" id="icon-file" />
                        <input
                            hidden
                            type="file"
                            name="documento"
                            id="documento"
                            onload="createPreview(this)"
                            value=""
                            disabled />
                        Download
                    </label>
                </span>
                <a href="<?php echo htmlspecialchars($pdf_url_path); ?>" target="_blank">
                    <p id="file-message">Visualizar: <?php echo htmlspecialchars($relatorioData["pdf_original_nome"]); ?>.pdf</p>
                </a>
            </div>
            <button
                type="submit"
                id="submitButton"
                class="button-primary"
                style="
                    --button-color: var(--fatec-red-500);
                    --button-color-hover: var(--fatec-red-400);
                ">
                Enviar
            </button>
            <p class="error-form"></p>
        </form>
    </section>
    <hr />

    <article class="status-container">
        <?php
        if (isset($relatorioData['id_feedback']) && isset($feedbackCount) && $feedbackCount > 0) {
            echo '<div class="feedback-summary">';
            echo '<h3>Histórico de Avaliações (' . $feedbackCount . ' avaliação' . ($feedbackCount > 1 ? 'ões' : '') . ')</h3>';
            echo '</div>';
        }

        if (!empty($allFeedbacks)) {
            foreach ($allFeedbacks as $index => $feedbackData) {
                $class = "status-" . strtolower($feedbackData->resultado);
                $feedbackNumber = $index + 1;

                echo '
                <div class="feedback-item" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                    <div class="feedback-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h4>Avaliação #' . $feedbackNumber . '</h4>
                        <div class="status-result status-result-' . strtolower($feedbackData->resultado) . '">
                            <span class="status-label ' . $class . '">Status: ' . htmlspecialchars($feedbackData->resultado) . '</span>
                            <span class="status-date">Data: ' . date("d/m/Y H:i", strtotime($feedbackData->data_envio)) . '</span>
                        </div>
                    </div>
                    
                    <div class="feedback-comments">
                        <h5>Comentários:</h5>';

                if (isset($feedbackData->comentarios)) {
                    foreach ($feedbackData->comentarios as $comment) {
                        echo '
                        <div class="status-comment" style="margin-bottom: 10px; margin-left:1.5rem; margin-top: 1rem;">
                            <img src="../../../public/icons/user.svg" alt="" />
                            <span>' . htmlspecialchars($comment->cargo) . " " . htmlspecialchars(explode(" ", $comment->docente_info->nome)[0]) . ':</span>
                            <textarea
                                class="textarea-primary"
                                disabled
                                id="textAreaOBS"">' . htmlspecialchars($comment->comentario_text) . '</textarea>
                        </div>';
                    }
                }

                echo '
                    </div>
                </div>';
            }
        }
        ?>

        <?php
        if (!$hasAnyFeedback) {
            $formAction = htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id;
            $userCargo = htmlspecialchars($_SESSION["user"]["cargo"]);
            $userPrimeiroNome = htmlspecialchars(explode(" ", $_SESSION["user"]["nome"])[0]);

            echo <<<HTML
            <form class="form-obs" action="$formAction" method="post">
                <h3>Observações:</h3>
                <div class="status-comment">
                    <img src="../../../public/icons/user.svg" alt="" />
                    <span>$userCargo $userPrimeiroNome:</span>
                    <textarea
                        name="observacao"
                        id="observacao"
                        class="textarea-primary"
                        oninput="autoSize(this)"
                        placeholder="Digite sua observação"
                        required></textarea>
                </div>
                <div class="buttons">
                    <button
                        class="button-primary"
                        style="
                            --button-color: var(--fatec-blue-500);
                            --button-color-hover: var(--fatec-blue-700);
                        "
                        type="submit"
                        name="status"
                        value="Aprovada">
                        Deferir
                    </button>
                    <button
                        class="button-primary"
                        style="
                            --button-color: var(--fatec-red-500);
                            --button-color-hover: var(--fatec-red-400);
                        "
                        type="submit"
                        name="status"
                        value="Reprovada">
                        Indeferir
                    </button>
                </div>
            </form>
        HTML;
        }

        ?>
    </article>

    <script src="../../../scripts/form-relatorio.js" defer></script>
    <script src="../../../components/header.js" defer></script>
</body>

</html>