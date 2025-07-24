<?php
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Inicializa a variável para evitar erro
$tarefas_por_status = null;

if (isLoggedIn()) {
    $tarefas = getUserTasks($pdo, $_SESSION['usuario_id']);

    $tarefas_por_status = [
        'A_FAZER' => [],
        'FAZENDO' => [],
        'REVISANDO' => [],
        'CONCLUIDO' => []
    ];

    foreach ($tarefas as $tarefa) {
        $tarefas_por_status[$tarefa['status']][] = $tarefa;
    }
}

// Cabeçalho HTML
include __DIR__ . '/templates/header.php';

// Lógica de exibição
if (!isLoggedIn()) {
    include __DIR__ . '/templates/auth_form.php';
} else {
    include __DIR__ . '/templates/kanban_board.php';
}

// Rodapé
include __DIR__ . '/templates/footer.php';
