<?php

include_once __DIR__ . "/Database.php";

class Relatorio
{
    private int $id_relatorio;
    private int $id_projeto;
    private string $data_entrega;
    private string $pdf_url;
    private string $pdf_nome;
    private string $descricoes;
    private string $pdf_original_nome;
    public Database $db;
    private string $diretorio;
    private string $allowed_extensions = "application/pdf";
    private int $max_weight = 47185920; //45mb

    public function __construct($id_relatorio = null, $id_projeto = null, $data_entrega = '', $pdf_url = '', $pdf_nome = '')
    {
        $this->db = new Database();
        $this->db->connect_to();
        $this->diretorio = dirname(__DIR__) . "/assets/uploads/relatorios/";

        if ($id_relatorio !== null) {
            $this->id_relatorio = $id_relatorio;
            $this->id_projeto = $id_projeto;
            $this->data_entrega = $data_entrega;
            $this->pdf_url = $pdf_url;
            $this->pdf_nome = $pdf_nome;
        }
    }

    public function getIdRelatorio()
    {
        return $this->id_relatorio;
    }

    public function getIdRelatorioByInscricao($id_inscricao)
    {
        $query = "SELECT id_relatorio FROM tb_relatorio WHERE id_projeto = :id_projeto";
        $id_projeto = $this->getIdProjetoByInscricao($id_inscricao);
        if (!$id_projeto) {
            return null;
        }
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_projeto', $id_projeto);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id_relatorio'] : null;
    }

    public function getRelatorios($id_docente)
    {
        $approved_inscricoes = "SELECT i.id_inscricao, i.data_envio, h.titulo as haeTitulo, h.tip_hae, p.titulo, p.data_final
                                FROM tb_inscricao i 
                                JOIN tb_hae h ON i.id_hae = h.id_hae 
                                JOIN tb_projeto p ON i.id_projeto = p.id_projeto  
                                LEFT JOIN tb_relatorio r ON p.id_projeto = r.id_projeto
                                WHERE i.status = 'Aprovada' 
                                AND i.id_docente = :id_docente 
                                AND r.id_relatorio IS NULL
                                ORDER BY i.data_envio DESC";
        $stmt = $this->db->get_PDO()->prepare($approved_inscricoes);
        $stmt->bindParam(':id_docente', $id_docente);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: [];
    }

    public function getRelatoriosEnviados($id_docente)
    {
        $enviados = "SELECT r.id_relatorio, r.data_entrega, r.pdf_url, r.pdf_nome, p.titulo as projetoTitulo, h.titulo as haeTitulo, h.tip_hae, i.id_inscricao
                     FROM tb_relatorio r 
                     JOIN tb_projeto p ON r.id_projeto = p.id_projeto 
                     JOIN tb_inscricao i ON i.id_projeto = p.id_projeto 
                     JOIN tb_hae h ON i.id_hae = h.id_hae 
                     WHERE i.id_docente = :id_docente 
                     ORDER BY r.data_entrega DESC";
        $stmt = $this->db->get_PDO()->prepare($enviados);
        $stmt->bindParam(':id_docente', $id_docente);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: [];
    }

    public function getRelatorioForm($id_inscricao, $prev_relatorio = false)
    {
        $query = "";
        if ($prev_relatorio === true) {
            $query = "SELECT i.id_inscricao, i.data_envio, 
            h.titulo as haeTitulo, h.tip_hae, 
            p.titulo, p.data_final, p.data_inicio, p.descricoes, p.id_projeto, 
            r.data_entrega, r.pdf_url, r.pdf_nome, r.descricoes as relatorioDescricoes, r.pdf_original_nome
            FROM tb_inscricao i 
            JOIN tb_hae h ON i.id_hae = h.id_hae 
            JOIN tb_projeto p ON i.id_projeto = p.id_projeto  
            JOIN tb_relatorio r ON p.id_projeto = r.id_projeto
            WHERE i.id_inscricao = :id_inscricao";
        } else {
            $query = "SELECT i.id_inscricao, i.data_envio, h.titulo as haeTitulo, h.tip_hae, p.titulo, p.data_final, p.data_inicio, p.descricoes, p.id_projeto
            FROM tb_inscricao i 
            JOIN tb_hae h ON i.id_hae = h.id_hae 
            JOIN tb_projeto p ON i.id_projeto = p.id_projeto  
            WHERE i.id_inscricao = :id_inscricao";
        }
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_inscricao', $id_inscricao);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: [];
    }

    private function getIdProjetoByInscricao($id_inscricao)
    {
        $query = "SELECT id_projeto FROM tb_inscricao WHERE id_inscricao = :id_inscricao";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_inscricao', $id_inscricao);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id_projeto'] : null;
    }

    public function createRelatorio($arquivo, $id_inscricao, $descricoes, $id_docente)
    {
        $id_projeto = $this->getIdProjetoByInscricao($id_inscricao);
        if (!$id_projeto) {
            return json_encode(["error" => "Não foi possível encontrar o projeto associado à inscrição."]);
        }

        if ($arquivo["type"] !== $this->allowed_extensions) {
            return json_encode(["error" => "Tipo de arquivo não permitido. Apenas PDF é aceito."]);
        }

        if ($arquivo["size"] > $this->max_weight) {
            return json_encode(["error" => "O arquivo excede o tamanho máximo permitido de 45MB."]);
        }

        $diretorioDocente = $this->diretorio . $id_docente . "/";
        if (!is_dir($diretorioDocente)) {
            if (!mkdir($diretorioDocente, 0775, true)) {
                return json_encode(["error" => "Erro ao criar diretório para o docente."]);
            }
        }

        $nomeOriginal = basename($arquivo["name"]);
        $extensaoPath = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
        $extensao     = strtolower($extensaoPath);
        $nomeUnico    = uniqid("relatorio_", true) . "_" . time() . "." . $extensao;
        $finalPath    = $diretorioDocente . $nomeUnico;
        $descricoes_insert = json_encode($descricoes);

        if (!move_uploaded_file($arquivo["tmp_name"], $finalPath)) {
            return json_encode(["error" => "Erro ao mover o arquivo para o diretório."]);
        }

        $query = "
            INSERT INTO tb_relatorio 
                (id_projeto, data_entrega, pdf_url, pdf_nome, descricoes, pdf_original_nome) 
            VALUES 
                (:id_projeto, NOW(), :pdf_url, :pdf_nome, :descricoes, :pdf_original_nome)
        ";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_projeto', $id_projeto);
        $stmt->bindParam(':pdf_url',   $finalPath);
        $stmt->bindParam(':pdf_nome',  $nomeUnico);
        $stmt->bindParam(':descricoes', $descricoes_insert);
        $stmt->bindParam(':pdf_original_nome', $nomeOriginal);

        if ($stmt->execute()) {
            $this->id_relatorio = $this->db->get_PDO()->lastInsertId();
            return true;
        } else {
            throw new Exception("Erro ao inserir o relatório no banco de dados.");
        }
    }

    public function getRelatorioById(int $id_relatorio): ?Relatorio
    {
        $query = "SELECT * FROM tb_relatorio WHERE id_relatorio = :id_relatorio";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_relatorio', $id_relatorio);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new self(
                $result['id_relatorio'],
                $result['id_projeto'],
                $result['data_entrega'],
                $result['pdf_url'],
                $result['pdf_nome']
            );
        }
        return null;
    }

    public function adminGetAllRelatorios($cargo, $curso)
    {
        if ($cargo === "Professor") {
            return [];
        }

        if ($cargo === "Coordenador") {
            $query = "SELECT r.*, p.titulo as projetoTitulo, h.titulo as haeTitulo, h.tip_hae, 
                             i.id_inscricao, d.nome as docenteNome, d.email as docenteEmail
                      FROM tb_relatorio r 
                      JOIN tb_projeto p ON r.id_projeto = p.id_projeto 
                      JOIN tb_inscricao i ON i.id_projeto = p.id_projeto 
                      JOIN tb_hae h ON i.id_hae = h.id_hae 
                      JOIN tb_docente d ON i.id_docente = d.id_docente
                      WHERE h.tip_hae = :curso
                      ORDER BY r.data_entrega DESC";
            $stmt = $this->db->get_PDO()->prepare($query);
            $stmt->bindParam(':curso', $curso);
            $stmt->execute();
        } else {
            $query = "SELECT r.*, p.titulo as projetoTitulo, h.titulo as haeTitulo, h.tip_hae, 
                             i.id_inscricao, d.nome as docenteNome, d.email as docenteEmail, r.data_entrega
                      FROM tb_relatorio r 
                      JOIN tb_projeto p ON r.id_projeto = p.id_projeto 
                      JOIN tb_inscricao i ON i.id_projeto = p.id_projeto 
                      JOIN tb_hae h ON i.id_hae = h.id_hae 
                      JOIN tb_docente d ON i.id_docente = d.id_docente
                      ORDER BY r.data_entrega DESC";
            $stmt = $this->db->get_PDO()->prepare($query);
            $stmt->execute();
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: [];
    }

    public function getRelatorioWithFeedback(int $id_inscricao)
    {
        $id_relatorio = $this->getIdRelatorioByInscricao($id_inscricao);

        $query = "SELECT r.*, 
                     p.titulo as projetoTitulo, p.descricoes as projetoDescricoes, p.id_hae, p.data_inicio, p.data_final,
                     h.titulo as haeTitulo, h.tip_hae, h.descricao as haeDescricao,
                     i.id_inscricao, i.data_envio as inscricaoDataEnvio,
                     d.nome as docenteNome, d.email as docenteEmail, d.cargo as docenteCargo, d.id_docente as docenteId
              FROM tb_relatorio r 
              JOIN tb_projeto p ON r.id_projeto = p.id_projeto 
              JOIN tb_inscricao i ON i.id_projeto = p.id_projeto 
              JOIN tb_hae h ON i.id_hae = h.id_hae 
              JOIN tb_docente d ON i.id_docente = d.id_docente
              WHERE r.id_relatorio = :id_relatorio";

        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_relatorio', $id_relatorio);
        $stmt->execute();
        $relatorio = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$relatorio) return null;


        $feedbackQuery = "
        SELECT f.id_feedback, f.data_envio, f.resultado,
               c.id_comentario, c.cargo as comentarioCargo, c.id_docente as comentarioDocenteId, c.comentario,
               d.nome as comentarioDocenteNome
        FROM tb_relatorio_feedback rf
        JOIN tb_feedback f ON rf.id_feedback = f.id_feedback
        LEFT JOIN tb_feedback_comentario c ON f.id_feedback = c.id_feedback
        LEFT JOIN tb_docente d ON c.id_docente = d.id_docente
        WHERE rf.id_relatorio = :id_relatorio
        ORDER BY f.data_envio DESC, c.id_comentario ASC
    ";

        $stmtFeedback = $this->db->get_PDO()->prepare($feedbackQuery);
        $stmtFeedback->bindParam(':id_relatorio', $id_relatorio);
        $stmtFeedback->execute();
        $feedbackRows = $stmtFeedback->fetchAll(PDO::FETCH_ASSOC);


        $feedbacks = [];
        foreach ($feedbackRows as $row) {
            $fid = $row['id_feedback'];
            if (!isset($feedbacks[$fid])) {
                $feedbacks[$fid] = [
                    'id_feedback' => $fid,
                    'data_envio' => $row['data_envio'],
                    'resultado' => $row['resultado'],
                    'comentarios' => []
                ];
            }

            if (!empty($row['id_comentario'])) {
                $feedbacks[$fid]['comentarios'][] = [
                    'id_comentario' => $row['id_comentario'],
                    'cargo' => $row['comentarioCargo'],
                    'id_docente' => $row['comentarioDocenteId'],
                    'docente_nome' => $row['comentarioDocenteNome'],
                    'comentario' => $row['comentario']
                ];
            }
        }

        $relatorio['feedbacks'] = array_values($feedbacks);
        return $relatorio;
    }


    public function calcRelatorioStatusByFeedbacks(int $id_relatorio)
    {
        include_once __DIR__ . "/Feedback.php";
        $feedback = new Feedback();


        $feedbacks = $this->getAllFeedbacksByRelatorio($id_relatorio);

        if (empty($feedbacks)) {
            return [
                'feedbackMessage' => 'Pendente',
                'feedbackCount' => 0,
                'feedbacks' => [],
                'plusMessage' => 'Aguardando avaliação...'
            ];
        }

        $feedbackCount = count($feedbacks);

        $latestFeedback = end($feedbacks);
        $feedbackMessage = $latestFeedback->resultado;
        $plusMessage = $latestFeedback->resultado === 'Aprovada'
            ? 'Parabéns! Seu relatório foi aprovado!'
            : 'Seu relatório precisa de correções.';

        return [
            'feedbackMessage' => $feedbackMessage,
            'feedbackCount' => $feedbackCount,
            'feedbacks' => $feedbacks,
            'plusMessage' => $plusMessage
        ];
    }

    public function getAllFeedbacksByRelatorio(int $id_relatorio)
    {
        include_once __DIR__ . "/Feedback.php";
        include_once __DIR__ . "/Docente.php";

        $query = "SELECT f.*, rf.id_relatorio_feedback,
                         fc.id_comentario, fc.cargo, fc.id_docente, fc.comentario,
                         d.nome, d.RG, d.email, d.matricula, d.turno, d.senha, d.cargo as docente_cargo, 
                         d.outras_fatecs, d.curso
                  FROM tb_relatorio_feedback rf
                  JOIN tb_feedback f ON rf.id_feedback = f.id_feedback
                  LEFT JOIN tb_feedback_comentario fc ON f.id_feedback = fc.id_feedback
                  LEFT JOIN tb_docente d ON fc.id_docente = d.id_docente
                  WHERE rf.id_relatorio = :id_relatorio
                  ORDER BY f.data_envio ASC";

        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_relatorio', $id_relatorio);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($results)) {
            return [];
        }

        $feedbacks = [];
        $currentFeedbackId = null;
        $currentFeedback = null;

        foreach ($results as $row) {

            if ($currentFeedbackId !== $row['id_feedback']) {
                if ($currentFeedback !== null) {
                    $feedbacks[] = $currentFeedback;
                }

                $currentFeedbackId = $row['id_feedback'];
                $currentFeedback = new Feedback(
                    $row['id_feedback'],
                    $row['id_inscricao'],
                    $row['resultado'],
                    $row['data_envio']
                );
                $currentFeedback->comentarios = [];
            }


            if ($row['id_comentario']) {
                $docente = null;
                if ($row['id_docente']) {
                    $docente = new Docente(
                        $row['id_docente'],
                        $row['nome'],
                        $row['RG'],
                        $row['email'],
                        $row['matricula'],
                        $row['turno'],
                        $row['senha'],
                        $row['docente_cargo'],
                        $row['outras_fatecs'],
                        $row['curso']
                    );
                }

                $currentFeedback->comentarios[] = new Comentario(
                    $row['id_comentario'],
                    $row['id_feedback'],
                    $row['cargo'],
                    $row['id_docente'],
                    $row['comentario'],
                    $docente
                );
            }
        }


        if ($currentFeedback !== null) {
            $feedbacks[] = $currentFeedback;
        }

        return $feedbacks;
    }

    public function getRelatoriosPendingFeedback($cargo, $curso)
    {
        if ($cargo === "Professor") {
            return [];
        }


        if ($cargo === "Coordenador") {
            $query = "SELECT r.*, p.titulo as projetoTitulo, h.titulo as haeTitulo, h.tip_hae, 
                             i.id_inscricao, d.nome as docenteNome
                      FROM tb_relatorio r 
                      JOIN tb_projeto p ON r.id_projeto = p.id_projeto 
                      JOIN tb_inscricao i ON i.id_projeto = p.id_projeto 
                      JOIN tb_hae h ON i.id_hae = h.id_hae 
                      JOIN tb_docente d ON i.id_docente = d.id_docente
                      LEFT JOIN tb_relatorio_feedback rf ON r.id_relatorio = rf.id_relatorio
                      WHERE h.tip_hae = :curso AND rf.id_relatorio_feedback IS NULL
                      ORDER BY r.data_entrega ASC";
            $stmt = $this->db->get_PDO()->prepare($query);
            $stmt->bindParam(':curso', $curso);
            $stmt->execute();
        } else {
            $query = "SELECT r.*, p.titulo as projetoTitulo, h.titulo as haeTitulo, h.tip_hae, 
                             i.id_inscricao, d.nome as docenteNome
                      FROM tb_relatorio r 
                      JOIN tb_projeto p ON r.id_projeto = p.id_projeto 
                      JOIN tb_inscricao i ON i.id_projeto = p.id_projeto 
                      JOIN tb_hae h ON i.id_hae = h.id_hae 
                      JOIN tb_docente d ON i.id_docente = d.id_docente
                      LEFT JOIN tb_relatorio_feedback rf ON r.id_relatorio = rf.id_relatorio
                      WHERE rf.id_relatorio_feedback IS NULL
                      ORDER BY r.data_entrega ASC";
            $stmt = $this->db->get_PDO()->prepare($query);
            $stmt->execute();
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: [];
    }

    public function createRelatorioFeedback(int $id_relatorio, string $resultado, string $cargo, int $id_docente, string $comentario_text): bool
    {
        include_once __DIR__ . "/Feedback.php";
        $feedback = new Feedback();

        try {
            $this->db->get_PDO()->beginTransaction();


            $relatorioData = $this->getRelatorioWithFeedback($id_relatorio);
            if (!$relatorioData) {
                throw new Exception("Relatório não encontrado");
            }

            $id_inscricao = $relatorioData['id_inscricao'];


            $id_feedback = $feedback->createFeedback($id_inscricao, $resultado, $cargo, $id_docente, $comentario_text);

            if (!$id_feedback) {
                throw new Exception("Erro ao criar feedback na tabela tb_feedback");
            }


            $linkResult = $this->linkFeedbackToRelatorio($id_relatorio, $id_feedback);

            if (!$linkResult) {

                $errorInfo = $this->db->get_PDO()->errorInfo();
                throw new Exception(
                    "Erro ao vincular feedback ao relatório. " .
                        "Error Code: {$errorInfo[0]}, Message: {$errorInfo[2]}"
                );
            }

            $this->db->get_PDO()->commit();
            return true;
        } catch (Exception $e) {
            $this->db->get_PDO()->rollBack();
            error_log("Erro ao criar feedback do relatório: " . $e->getMessage());
            return false;
        }
    }

    private function linkFeedbackToRelatorio(int $id_relatorio, int $id_feedback): bool
    {
        $query = "INSERT INTO tb_relatorio_feedback (id_relatorio, id_feedback) VALUES (:id_relatorio, :id_feedback)";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_relatorio', $id_relatorio);
        $stmt->bindParam(':id_feedback', $id_feedback);

        try {
            $result = $stmt->execute();
            if (!$result) {
                error_log("Insert failed: " . implode(", ", $stmt->errorInfo()));
            }
            return $result && $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("PDO Exception in linkFeedbackToRelatorio: " . $e->getMessage());
            return false;
        }
    }

    public function hasAnyFeedback(int $id_relatorio): bool
    {
        $query = "SELECT COUNT(*) as count FROM tb_relatorio_feedback WHERE id_relatorio = :id_relatorio";
        $stmt = $this->db->get_PDO()->prepare($query);
        $stmt->bindParam(':id_relatorio', $id_relatorio);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result && $result['count'] > 0;
    }
}
