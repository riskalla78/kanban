<?php
require_once __DIR__ . '/../config/database.php';
require_once 'functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}

if (!isset($_GET['tarefa_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID da tarefa não informado']);
    exit;
}

$tarefa_id = $_GET['tarefa_id'];

$stmt = $pdo->prepare("SELECT t.*, u.nome AS nome_criador FROM tarefas t JOIN usuarios u ON t.usuario_id = u.usuario_id WHERE tarefa_id = ?");
$stmt->execute([$tarefa_id]);
$tarefa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tarefa) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Tarefa não encontrada']);
    exit;
}

if (!isManager() && $tarefa['usuario_id'] != $_SESSION['usuario_id']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}

echo json_encode(['success' => true, 'task' => $tarefa]);
exit;
