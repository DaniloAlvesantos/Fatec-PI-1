<?php

include_once __DIR__ . "/Database.php";

class Feedback
{
    public Database $db;
    public ?int $id_feedback = null;
    public ?int $id_inscricao = null;
    public ?string $resultado = null;

    public function __construct($id_feedback = null)
    {
        $this->db = new Database();
        $this->db->connect_to();

        if ($id_feedback) {
            $this->id_feedback = $id_feedback;
            $this->loadFeedback();
        }
    }

    private function loadFeedback()
    {
        $pdo = $this->db->get_PDO();

        if (!$pdo) return;

        $stmt = $pdo->prepare("SELECT * FROM tb_feedback WHERE id_feedback = ?");
        $stmt->execute([$this->id_feedback]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id_inscricao = $row['id_inscricao'];
            $this->resultado = $row['resultado'];
        }
    }

    public function createFeedback($id_inscricao, $resultado, $cargo, $id_docente, $comentario): ?int
    {
        $pdo = $this->db->get_PDO();
        if (!$pdo) return null;

        try {
            $pdo->beginTransaction();

            $stmt1 = $pdo->prepare("INSERT INTO tb_feedback (id_inscricao, resultado) VALUES (?, ?)");
            $stmt1->execute([$id_inscricao, $resultado]);

            $id_feedback = (int)$pdo->lastInsertId();

            $stmt2 = $pdo->prepare("INSERT INTO tb_feedback_comentario (id_feedback, cargo, id_docente, comentario) VALUES (?, ?, ?, ?)");
            $stmt2->execute([$id_feedback, $cargo, $id_docente, $comentario]);
            $pdo->commit();

            return $id_feedback;
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "❌ Error creating feedback: " . $e->getMessage();
            return null;
        }
    }

    public function getComentarios(): array
    {
        if (!$this->id_feedback) return [];

        $pdo = $this->db->get_PDO();
        if (!$pdo) return [];

        $stmt = $pdo->prepare("SELECT * FROM tb_feedback_comentario WHERE id_feedback = ?");
        $stmt->execute([$this->id_feedback]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
