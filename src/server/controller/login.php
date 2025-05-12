<?php

require "../model/Login.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['email']) || !isset($input['senha'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

$login = new Login();
$docente = $login->execLogin($input["email"], $input["senha"]);

if (!$docente) {
    echo json_encode(['success' => false, 'message' => 'Usuário não encontrado']);
    exit;
}

// Corrigido: alterado 'sucess' para 'success'
echo json_encode(['success' => true, 'user' => $docente]);
