<?php

require "../model/HAE.php";
require "../model/Docente.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if(isset($input)) {
    echo json_encode(['success' => false, 'message' => 'Dados vazios']);
}

$hae = new HAE();
$docente = new Docente();

$query = "INSERT INTO tb_hae (titulo, tip_hae, quant_hae, descricao, data_inicio, data_final) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $hae->db->get_PDO()->prepare($query);
$stmt->bindParam(1, $input['titulo']);
$stmt->bindParam(2, $input['tip_hae']);
$stmt->bindParam(3, $input['quant_hae']);
$stmt->bindParam(4, $input['descricao']);
$stmt->bindParam(5, $input['data_inicio']);
$stmt->bindParam(6, $input['data_final']);
$stmt->execute();
echo $stmt;