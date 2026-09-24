<?php
session_start();
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/automacao_sistema.php';

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

// Consultar notificações não lidas do utilizador
$minhasNotificacoes = [];
try {
    $stmtNotif = $pdo->prepare("SELECT * FROM notificacoes WHERE id_utilizador = :user_id AND lida = 0 ORDER BY data_criacao DESC");
    $stmtNotif->execute([':user_id' => $userId]);
    $minhasNotificacoes = $stmtNotif->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Ignores erro se a tabela ainda não existir
    $minhasNotificacoes = [];
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

    <!-- Bloco de Alertas de Notificações -->
    <?php if (!empty($minhasNotificacoes)): ?>
        <div style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <h4 style="margin-top: 0; margin-bottom: 10px; font-size: 16px;">
                🔔 Novas Notificações (<?php echo count($minhasNotificacoes); ?>)
            </h4>
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach ($minhasNotificacoes as $n): ?>
                    <li style="margin-bottom: 5px;">
                        <?php echo htmlspecialchars($n['mensagem']); ?>
                        <small style="color: #6c757d;">(<?php echo date('d/m/Y H:i', strtotime($n['data_criacao'])); ?>)</small>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

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