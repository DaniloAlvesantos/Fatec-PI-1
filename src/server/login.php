<?php
header('Content-Type: application/json');
 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}
 
// Pega JSON enviado no corpo da requisição
$input = json_decode(file_get_contents('php://input'), true);
 
// Validação básica
if (!isset($input['email']) || !isset($input['senha'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}
 
// Simulação de "banco de dados"
$usuarios = [
    [
        "name" => "João Silva",
        "email" => "joao@fatec.com",
        "senha" => "123456",
        "cargo" => "Professor"
    ],
    [
        "name" => "Maria Prof",
        "email" => "maria@fatec.com",
        "senha" => "senhaSegura",
        "cargo" => "Coordenadora"
    ]
];
 
foreach ($usuarios as $usuario) {
    if ($usuario['email'] === $input['email']) {
        if ($usuario['senha'] === $input['senha']) {
            echo json_encode([
                'success' => true,
                'user' => [
                    'name' => $usuario['name'],
                    'email' => $usuario['email'],
                    'cargo' => $usuario['cargo']
                ]
            ]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Senha incorreta']);
            exit;
        }
    }
}
 
echo json_encode(['success' => false, 'message' => 'Usuário não encontrado']);