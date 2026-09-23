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
        $erro = 'E-mail inválido.';
    } elseif ($senha !== $confirmar) {
        $erro = 'As palavras-passe não coincidem.';
    } else {
        if (obterUtilizadorPorEmail($pdo, $email)) {
            $erro = 'Já existe um utilizador com este e-mail.';
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            if (registarUtilizador($pdo, $nome, $email, $hash)) {
                $sucesso = 'Registo efetuado com sucesso! Já pode fazer login.';
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appo - Registo</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="container">
        <div class="card">
            <h2>Appo — Agendamentos</h2>
            <h3>Criar Conta</h3>

            <?php if ($erro !== ''): ?>
                <div class="alert erro"><?= htmlspecialchars($erro); ?></div>
            <?php endif; ?>

            <?php if ($sucesso !== ''): ?>
                <div class="alert sucesso"><?= htmlspecialchars($sucesso); ?></div>
            <?php endif; ?>

            <form id="form-registo" action="registo.php" method="POST" novalidate>
                <div class="form-group">
                    <label for="nome">Nome completo</label>
                    <input type="text" id="nome" name="nome" placeholder="O seu nome">
                    <span class="mensagem-erro" id="erro-nome">Insira o seu nome.</span>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="seu@email.com">
                    <span class="mensagem-erro" id="erro-email">Insira um e-mail válido.</span>
                </div>

                <div class="form-group">
                    <label for="senha">Palavra-passe</label>
                    <input type="password" id="senha" name="senha" placeholder="******">
                    <span class="mensagem-erro" id="erro-senha">Insira a palavra-passe.</span>
                </div>

                <div class="form-group">
                    <label for="confirmar">Confirmar palavra-passe</label>
                    <input type="password" id="confirmar" name="confirmar" placeholder="******">
                    <span class="mensagem-erro" id="erro-confirmar">As palavras-passe não coincidem.</span>
                </div>

                <button type="submit" class="btn">Registar</button>
            </form>

            <div class="link-box">
                <p>Já tem conta? <a href="login.php">Entrar</a></p>
            </div>
        </div>
    </div>

    <script src="../js/validacao.js" defer></script>
</body>
</html>