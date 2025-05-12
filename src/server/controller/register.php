<?php

include "../model/Login.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Retrieve and sanitize input
$nome = trim($_POST["name"] ?? '');
$rg = trim($_POST["RG"] ?? '');
$email = trim($_POST["email"] ?? '');
$matricula = trim($_POST["matricula"] ?? '');
$turno = trim($_POST["turno"] ?? '');
$senha = trim($_POST["senha"] ?? '');
$confirmar_senha = trim($_POST["confirmar_senha"] ?? '');
$cargo = trim($_POST["cargo"] ?? '');
$outrasFatecs = isset($_POST['outras_fatecs']) ? 1 : 0;
$curso = trim($_POST["curso"] ?? '');

// Validate input fields
if (empty($nome) || empty($rg) || empty($email) || empty($matricula) || empty($turno) || empty($senha) || empty($confirmar_senha) || empty($cargo) || empty($curso)) {
    echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios']);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email inválido']);
    exit;
}

// Check if passwords match
if ($senha !== $confirmar_senha) {
    echo json_encode(['success' => false, 'message' => 'As senhas não coincidem']);
    exit;
}

// Attempt to register the user
$login = new Login();
$result = $login->execRegister($nome, $rg, $email, $matricula, $turno, $senha, $cargo, $outrasFatecs, $curso);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Registro realizado com sucesso']);
} else {
    echo json_encode(['success' => false, 'message' => 'Falha ao registrar. Tente novamente mais tarde']);
}
