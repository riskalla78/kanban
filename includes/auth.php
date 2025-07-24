<?php
require_once __DIR__ . '/../config/database.php';
require_once 'functions.php';

$login_error = $register_error = $success_msg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['usuario_id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['tipo'] = $usuario['tipo'];
            header("Location: index.php");
            exit;
        } else {
            $login_error = "Credenciais inválidas!";
        }
    } elseif (isset($_POST['register'])) {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        // Verifica se email já existe
        $stmtCheck = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmtCheck->execute([$email]);
        if ($stmtCheck->fetch()) {
            $register_error = "Este email já está cadastrado.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'USUARIO')");
                $stmt->execute([$nome, $email, $senha_hash]);
                $success_msg = "Conta criada com sucesso! Faça login.";
            } catch (PDOException $e) {
                $register_error = "Erro ao criar conta: " . $e->getMessage();
            }
        }
    }
}
