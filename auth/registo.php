<?php
session_start();
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

if (isLoggedIn()) {
    header('Location: ../painel/home.php');
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    if ($nome === '' || $email === '' || $senha === '' || $confirmar === '') {
        $erro = 'Por favor, preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Email inválido.';
    } elseif ($senha !== $confirmar) {
        $erro = 'As senhas não coincidem.';
    } else {
        if (obterUtilizadorPorEmail($pdo, $email)) {
            $erro = 'Já existe um utilizador com este email.';
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            if (registarUtilizador($pdo, $nome, $email, $hash)) {
                $sucesso = 'Registo efetuado com sucesso. Já pode fazer login.';
            } else {
                $erro = 'Ocorreu um erro ao registar. Tente novamente.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Appo - Registo</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/validacao.js" defer></script>
</head>
<body>
<div class="container">
    <h1>Appo — Agendamentos Online</h1>
    <h2>Registo</h2>

    <?php if ($erro): ?>
        <div class="alert erro"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <div class="alert sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
    <?php endif; ?>

    <form id="form-registo" method="post" action="registo.php" onsubmit="return validarRegisto();">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>
        </div>

        <div class="form-group">
            <label for="confirmar">Confirmar senha:</label>
            <input type="password" name="confirmar" id="confirmar" required>
        </div>

        <div class="form-actions">
            <button type="submit">Registar</button>
        </div>

        <p class="link">
            Já tem conta?
            <a href="login.php">Entrar</a>
        </p>
    </form>
</div>
</body>
</html>
