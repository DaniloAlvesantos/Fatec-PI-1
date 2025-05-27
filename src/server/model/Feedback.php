<?php

include_once __DIR__ . "/Database.php";
include_once __DIR__ . "/Docente.php";


class Comentario
{
    public ?int $id_comentario = null;
    public ?int $id_feedback = null;
    public ?string $cargo = null;
    public ?int $id_docente = null;
    public ?string $comentario_text = null;
    public ?Docente $docente_info = null;

    public function __construct($id_comentario, $id_feedback, $cargo, $id_docente, $comentario_text, $docente_info = null)
    {
        $this->id_comentario = $id_comentario;
        $this->id_feedback = $id_feedback;
        $this->cargo = $cargo;
        $this->id_docente = $id_docente;
        $this->comentario_text = $comentario_text;
        $this->docente_info = $docente_info;
    }
}

class Feedback
{
    public Database $db;
    public ?int $id_feedback = null;
    public ?int $id_inscricao = null;
    public ?string $resultado = null;
    public string $data_envio;
    public array $comentarios = [];

    public function __construct($id_feedback = null, $id_inscricao = null, $resultado = null, $data_envio = '')
    {
        $this->db = new Database();
        $this->db->connect_to();

        if ($id_feedback) {
            $this->id_feedback = $id_feedback;
            $this->id_inscricao = $id_inscricao;
            $this->resultado = $resultado;
            $this->data_envio = $data_envio;
        }
    }


    public function createFeedback($id_inscricao, $resultado, $cargo, $id_docente, $comentario_text): ?int
    {
        $pdo = $this->db->get_PDO();
        if (!$pdo) return null;

        try {
            $pdo->beginTransaction();

            $stmt1 = $pdo->prepare("INSERT INTO tb_feedback (id_inscricao, resultado) VALUES (?, ?)");
            $stmt1->execute([$id_inscricao, $resultado]);

            $id_feedback = (int)$pdo->lastInsertId();

            $stmt2 = $pdo->prepare("INSERT INTO tb_feedback_comentario (id_feedback, cargo, id_docente, comentario) VALUES (?, ?, ?, ?)");
            $stmt2->execute([$id_feedback, $cargo, $id_docente, $comentario_text]);
            $pdo->commit();

            return $id_feedback;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("❌ Error creating feedback: " . $e->getMessage());
            return null;
        }
    }


    public function addCommentToExistingFeedback(int $id_feedback, string $cargo, int $id_docente, string $comentario_text): bool
    {
        $pdo = $this->db->get_PDO();
        if (!$pdo) return false;

        try {
            $stmt = $pdo->prepare("INSERT INTO tb_feedback_comentario (id_feedback, cargo, id_docente, comentario) VALUES (?, ?, ?, ?)");
            $stmt->execute([$id_feedback, $cargo, $id_docente, $comentario_text]);
            return true;
        } catch (PDOException $e) {
            error_log("❌ Error adding comment to existing feedback: " . $e->getMessage());
            return false;
        }
    }


    public function getAllFeedbacksByInscricao(int $id_inscricao): array
    {
        $pdo = $this->db->get_PDO();
        if (!$pdo) return [];

        try {

            $stmt_feedbacks = $pdo->prepare("SELECT * FROM tb_feedback WHERE id_inscricao = ? ORDER BY data_envio ASC");
            $stmt_feedbacks->execute([$id_inscricao]);
            $feedbacks_data = $stmt_feedbacks->fetchAll(PDO::FETCH_ASSOC);

            if (!$feedbacks_data) {
                return [];
            }

            $feedbacks = [];

            foreach ($feedbacks_data as $feedback_data) {

                $feedback = new self(
                    $feedback_data['id_feedback'],
                    $feedback_data['id_inscricao'],
                    $feedback_data['resultado'],
                    $feedback_data['data_envio']
                );


                $stmt_comments = $pdo->prepare("
                    SELECT 
                        fc.id_comentario,
                        fc.id_feedback,
                        fc.cargo,
                        fc.id_docente,
                        fc.comentario,
                        d.id_docente as docente_id,
                        d.nome,
                        d.RG,
                        d.email,
                        d.matricula,
                        d.turno,
                        d.senha,
                        d.cargo as docente_cargo,
                        d.outras_fatecs,
                        d.curso
                    FROM tb_feedback_comentario fc
                    LEFT JOIN tb_docente d ON fc.id_docente = d.id_docente
                    WHERE fc.id_feedback = ?
                    ORDER BY fc.id_comentario ASC
                ");

                $stmt_comments->execute([$feedback->id_feedback]);
                $comments_data = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

                foreach ($comments_data as $comment_row) {
                    $docente = null;
                    if ($comment_row['docente_id']) {
                        $docente = new Docente(
                            $comment_row['docente_id'],
                            $comment_row['nome'],
                            $comment_row['RG'],
                            $comment_row['email'],
                            $comment_row['matricula'],
                            $comment_row['turno'],
                            $comment_row['senha'],
                            $comment_row['docente_cargo'],
                            $comment_row['outras_fatecs'],
                            $comment_row['curso']
                        );
                    }
                    $feedback->comentarios[] = new Comentario(
                        $comment_row['id_comentario'],
                        $comment_row['id_feedback'],
                        $comment_row['cargo'],
                        $comment_row['id_docente'],
                        $comment_row['comentario'],
                        $docente
                    );
                }

                $feedbacks[] = $feedback;
            }

            return $feedbacks;
        } catch (PDOException $e) {
            error_log("Error in getAllFeedbacksByInscricao: " . $e->getMessage());
            return [];
        }
    }


    public function getFeedbackByInscricao(int $id_inscricao): ?Feedback
    {
        $allFeedbacks = $this->getAllFeedbacksByInscricao($id_inscricao);

        if (empty($allFeedbacks)) {
            return null;
        }


        return end($allFeedbacks);
    }

    public function getLatestFeedbackByInscricao(int $id_inscricao): ?Feedback
    {
        return $this->getFeedbackByInscricao($id_inscricao);
    }

    public function getFirstFeedbackByInscricao(int $id_inscricao): ?Feedback
    {
        $allFeedbacks = $this->getAllFeedbacksByInscricao($id_inscricao);

        if (empty($allFeedbacks)) {
            return null;
        }

        return $allFeedbacks[0];
    }

    public function countFeedbacksByInscricao(int $id_inscricao): int
    {
        $pdo = $this->db->get_PDO();
        if (!$pdo) return 0;

        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM tb_feedback WHERE id_inscricao = ?");
            $stmt->execute([$id_inscricao]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'];
        } catch (PDOException $e) {
            error_log("Error in countFeedbacksByInscricao: " . $e->getMessage());
            return 0;
        }
    }

    public function calcStatusByFeedbacks(int $id_inscricao)
    {
        $feedbackMessage = "";
        $feedbackCount = $this->countFeedbacksByInscricao($id_inscricao);

        if ($feedbackCount === 0) {
            $feedbackMessage = "Pendente";
        }

        $query = "SELECT * FROm tb_feedback WHERE id_inscricao = ? ORDER BY data_envio DESC";
        $pdo = $this->db->get_PDO();
        $smt = $pdo->prepare($query);
        $smt->execute([$id_inscricao]);
        $feedbacks = $smt->fetchAll(PDO::FETCH_ASSOC);

        $countApro = 0;
        $countRepro = 0;

        foreach ($feedbacks as $feedback) {
            if ($feedback['resultado'] === "Aprovada") {
                $countApro++;
            } else {
                $countRepro++;
            }
        }

        $plusMessage = "";
        if ($countApro === 0 || $countRepro > $countApro) {
            $feedbackMessage = "Reprovada";
            $plusMessage = "Desta vez, você não conseguiu. Desta vez, tá!?";
        } elseif ($countApro > $countRepro) {
            $feedbackMessage = "Aprovada";
            $plusMessage = "Parabéns! Você foi aprovada!";
        } else {
            $feedbackMessage = "Pendente";
            $plusMessage = "Ainda não temos uma decisão final sobre sua inscrição.";
        }


        $feedbacks = $this->getAllFeedbacksByInscricao($id_inscricao);
        
        return [
            'feedbackMessage' => $feedbackMessage,
            'feedbackCount' => $feedbackCount,
            'feedbacks' => $feedbacks,
            'plusMessage' => $plusMessage,
        ];
    }
}
