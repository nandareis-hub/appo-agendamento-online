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

$mensagemErro = '';
$mensagemSucesso = '';

// Cancelar marcação
if (isset($_GET['cancelar'])) {
    $idMarcacao = intval($_GET['cancelar']);
    try {
        $stmt = $pdo->prepare("DELETE FROM marcacoes WHERE id_marcacao = :id AND id_utilizador = :user_id");
        $stmt->execute([':id' => $idMarcacao, ':user_id' => $userId]);
        
        if ($stmt->rowCount() > 0) {
            $mensagemSucesso = "Marcação cancelada com sucesso!";
        }
    } catch (PDOException $e) {
        $mensagemErro = "Erro ao cancelar marcação.";
    }
}

// Consultar marcações com os nomes dos serviços
$marcacoes = [];
try {
    $sql = "SELECT m.id_marcacao, m.data_marcacao, s.nome AS servico_nome 
            FROM marcacoes m 
            LEFT JOIN servicos s ON m.id_servico = s.id_servico 
            WHERE m.id_utilizador = :user_id 
            ORDER BY m.id_marcacao DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId]);
    $marcacoes = $stmt->fetchAll();
} catch (PDOException $e) {
    // Caso ocorra erro de coluna
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appo - Minhas Marcações</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container layout-painel">
    <header class="topo">
        <div class="brand">
            <h1>Appo — Agendamentos Online</h1>
        </div>
        <div class="user-info">
            <span>Olá, <strong><?= htmlspecialchars($nomeUtilizador) ?></strong> (<?= htmlspecialchars($tipoUtilizador) ?>)</span>
            <a class="btn-sair" href="../auth/logout.php">Sair</a>
        </div>
    </header>

    <nav class="menu">
        <a href="home.php">Início</a>
        <a href="minhas-marcacoes.php" class="active">Minhas marcações</a>
        <a href="nova-marcacao.php" class="btn-destaque">+ Nova marcação</a>
    </nav>

    <main class="card-conteudo">
        <h2>Minhas marcações</h2>

        <?php if (!empty($mensagemErro)): ?>
            <div class="alert erro"><?= htmlspecialchars($mensagemErro) ?></div>
        <?php endif; ?>

        <?php if (!empty($mensagemSucesso)): ?>
            <div class="alert sucesso"><?= htmlspecialchars($mensagemSucesso) ?></div>
        <?php endif; ?>

        <?php if (empty($marcacoes)): ?>
            <div class="sem-dados" style="padding: 20px; text-align: center;">
                <p>Ainda não efetuou nenhuma marcação.</p>
                <a href="nova-marcacao.php" class="btn btn-destaque" style="display:inline-block; margin-top:10px; background:#28a745; color:white; padding:10px 20px; border-radius:4px; text-decoration:none;">Criar primeira marcação</a>
            </div>
        <?php else: ?>
            <div class="table-responsive" style="margin-top: 15px;">
                <table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #ccc; text-align:left;">
                            <th style="padding:10px;">Serviço</th>
                            <th style="padding:10px;">Data e Hora</th>
                            <th style="padding:10px;">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($marcacoes as $m): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding:10px;"><?= htmlspecialchars($m['servico_nome'] ?? 'Serviço Agendado') ?></td>
                                <td style="padding:10px;"><?= !empty($m['data_marcacao']) ? date('d/m/Y H:i', strtotime($m['data_marcacao'])) : 'Data a definir' ?></td>
                                <td style="padding:10px;">
                                    <a href="minhas-marcacoes.php?cancelar=<?= $m['id_marcacao'] ?>" 
                                       style="background-color: #dc3545; color: white; padding: 6px 12px; border-radius:4px; text-decoration:none; font-size:14px;"
                                       onclick="return confirm('Tem a certeza que deseja cancelar?');">
                                       Cancelar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</div>

</body>
</html>