<?php
session_start();
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

if (!isLoggedIn()) {
    header('Location: ../auth/login.php');
    exit;
}

$nomeUtilizador = $_SESSION['user_nome'] ?? 'Cliente';
$tipoUtilizador = $_SESSION['user_tipo'] ?? 'cliente';
$userId = $_SESSION['user_id'];

// Consultar o total de marcações do utilizador
$totalMarcacoes = 0;
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM marcacoes WHERE id_utilizador = :user_id");
    $stmt->execute([':user_id' => $userId]);
    $totalMarcacoes = $stmt->fetchColumn();
} catch (PDOException $e) {
    // Ignora erro de tabela inexistente
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appo - Painel Principal</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container layout-painel">

    <!-- CABEÇALHO -->
    <header class="topo">
        <div class="brand">
            <h1>Appo — Agendamentos Online</h1>
        </div>
        <div class="user-info">
            <span>Olá, <strong><?= htmlspecialchars($nomeUtilizador) ?></strong> (<?= htmlspecialchars($tipoUtilizador) ?>)</span>
            <a class="btn-sair" href="../auth/logout.php">Sair</a>
        </div>
    </header>

    <!-- NAVEGAÇÃO -->
    <nav class="menu">
        <a href="home.php" class="active">Início</a>
        <a href="minhas-marcacoes.php">Minhas marcações</a>
        <a href="nova-marcacao.php" class="btn-destaque">+ Nova marcação</a>
    </nav>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="card-conteudo">
        <h2>Resumo de marcações</h2>

        <?php if ($totalMarcacoes > 0): ?>
            <div class="alert sucesso">
                Possui <strong><?= $totalMarcacoes ?></strong> marcação(ões) registada(s) no sistema.
            </div>
        <?php else: ?>
            <p>Não existem marcações registadas.</p>
        <?php endif; ?>

        <div class="cliente-info">
            <h3>Área do cliente</h3>
            <p>Use o menu superior para agendar novos horários ou consultar e gerir os seus agendamentos ativos.</p>
        </div>
    </main>
</div>

</body>
</html>