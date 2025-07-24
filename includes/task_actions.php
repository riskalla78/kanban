<?php
require_once __DIR__ . '/../config/database.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}
if (isset($_POST['edit_task'])) {
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Não autorizado']);
        exit;
    }

    $id = $_POST['tarefa_id'];
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $prioridade = $_POST['prioridade'];

    $stmt = $pdo->prepare("UPDATE tarefas SET titulo = ?, descricao = ?, prioridade = ? WHERE tarefa_id = ?");
    $stmt->execute([$titulo, $descricao, $prioridade, $id]);

    echo json_encode(['success' => true]);
    exit;
}

// Adicionar tarefa
if (isset($_POST['add_task'])) {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'] ?? '';
    $prioridade = $_POST['prioridade'];

    $stmt = $pdo->prepare("INSERT INTO tarefas (titulo, descricao, prioridade, status, usuario_id, data_criacao) VALUES (?, ?, ?, 'A_FAZER', ?, NOW())");
    $stmt->execute([$titulo, $descricao, strtoupper($prioridade), $_SESSION['usuario_id']]);
    header("Location: ../index.php");
    exit;
}

// Atualizar status da tarefa (drag & drop)
if (isset($_POST['update_status'])) {
    $tarefa_id = $_POST['tarefa_id'];
    $novo_status = $_POST['novo_status'];

    // Verifica permissão: gerente pode qualquer tarefa, usuário só as suas
    $stmtCheck = $pdo->prepare("SELECT usuario_id FROM tarefas WHERE tarefa_id = ?");
    $stmtCheck->execute([$tarefa_id]);
    $tarefa = $stmtCheck->fetch(PDO::FETCH_ASSOC);
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

    // Se status for CONCLUIDO, adiciona data_conclusao
    if ($novo_status === 'CONCLUIDO') {
        $stmt = $pdo->prepare("UPDATE tarefas SET status = ?, data_conclusao = NOW() WHERE tarefa_id = ?");
        $stmt->execute([$novo_status, $tarefa_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE tarefas SET status = ?, data_conclusao = NULL WHERE tarefa_id = ?");
        $stmt->execute([$novo_status, $tarefa_id]);
    }
    echo json_encode(['success' => true]);
    exit;
}

// Excluir tarefa
if (isset($_POST['delete_task'])) {
    $tarefa_id = $_POST['tarefa_id'];

    // Verifica permissão
    $stmtCheck = $pdo->prepare("SELECT usuario_id FROM tarefas WHERE tarefa_id = ?");
    $stmtCheck->execute([$tarefa_id]);
    $tarefa = $stmtCheck->fetch(PDO::FETCH_ASSOC);
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

    $stmt = $pdo->prepare("DELETE FROM tarefas WHERE tarefa_id = ?");
    $stmt->execute([$tarefa_id]);
    echo json_encode(['success' => true]);
    exit;
}
