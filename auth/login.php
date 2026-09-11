<?php
session_start();
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

if (isLoggedIn()) {
    header('Location: ../painel/home.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        $utilizador = obterUtilizadorPorEmail($pdo, $email);
        if ($utilizador && password_verify($senha, $utilizador['senha'])) {
            $_SESSION['user_id'] = $utilizador['id'];
            $_SESSION['user_nome'] = $utilizador['nome'];
            $_SESSION['user_email'] = $utilizador['email'];
            $_SESSION['user_tipo'] = $utilizador['tipo'];
            header('Location: ../painel/home.php');
            exit;
        } else {
            $erro = 'Credenciais inválidas.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Appo - Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/validacao.js" defer></script>
</head>
<body>
<div class="container">
    <h1>Appo — Agendamentos Online</h1>
    <h2>Login</h2>

    <?php if ($erro): ?>
        <div class="alert erro"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <form id="form-login" method="post" action="login.php" onsubmit="return validarLogin();">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>
        </div>

        <div class="form-actions">
            <button type="submit">Entrar</button>
        </div>

        <p class="link">
            Ainda não tem conta?
            <a href="registo.php">Registar</a>
        </p>
    </form>
</div>
</body>
</html>
