<?php
require_once __DIR__ . '/session.php';
//Funções auxiliares

function isLoggedIn()
{ //verifica se existe usuario_id na sessão atual
    return isset($_SESSION['usuario_id']);
}

function isManager()
{ //determina os privilégios do usuário
    return isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'GERENTE';
}

function getUserTasks($pdo, $usuario_id)
{ //Consulta condicional do banco de dados. Se for gerente, tem acesso a todas as tarefas. O usuário apenas às suas próprias.
    if (isManager()) {
        $stmt = $pdo->prepare("SELECT t.*, u.nome AS nome_criador 
                       FROM tarefas t 
                       JOIN usuarios u ON t.usuario_id = u.usuario_id
                       ORDER BY 
                         FIELD(t.prioridade, 'ALTA', 'MEDIA', 'BAIXA'), 
                         t.data_criacao DESC");

        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("SELECT t.*, u.nome AS nome_criador 
                       FROM tarefas t 
                       JOIN usuarios u ON t.usuario_id = u.usuario_id 
                       WHERE t.usuario_id = ?
                       ORDER BY 
                         FIELD(t.prioridade, 'ALTA', 'MEDIA', 'BAIXA'), 
                         t.data_criacao DESC");

        $stmt->execute([$usuario_id]);
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
