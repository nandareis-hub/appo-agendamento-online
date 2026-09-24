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
        $mensagemErro = "Erro ao cancelar marcação: " . $e->getMessage();
    }
}

// Listar as marcações do utilizador ligado às tabelas de serviços e profissionais
$marcacoes = [];
try {
    $sql = "SELECT m.id_marcacao, m.data, m.hora, m.estado, 
                   s.nome AS servico_nome, s.preco, 
                   p.nome AS profissional_nome 
            FROM marcacoes m 
            JOIN servicos s ON m.id_servico = s.id_servico 
            JOIN profissionais p ON m.id_profissional = p.id_profissional 
            WHERE m.id_utilizador = :user_id 
            ORDER BY m.data DESC, m.hora DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId]);
    $marcacoes = $stmt->fetchAll();
} catch (PDOException $e) {
    $mensagemErro = "Erro ao consultar marcações: " . $e->getMessage();
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
            <div class="alert erro" style="background:#f8d7da; color:#721c24; padding:10px; border-radius:4px; margin-bottom:15px;"><?= htmlspecialchars($mensagemErro) ?></div>
        <?php endif; ?>

        <?php if (!empty($mensagemSucesso)): ?>
            <div class="alert sucesso" style="background:#d4edda; color:#155724; padding:10px; border-radius:4px; margin-bottom:15px;"><?= htmlspecialchars($mensagemSucesso) ?></div>
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
                            <th style="padding:10px;">Profissional</th>
                            <th style="padding:10px;">Data e Hora</th>
                            <th style="padding:10px;">Preço</th>
                            <th style="padding:10px;">Estado</th>
                            <th style="padding:10px;">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($marcacoes as $m): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding:10px;"><?= htmlspecialchars($m['servico_nome']) ?></td>
                                <td style="padding:10px;"><?= htmlspecialchars($m['profissional_nome']) ?></td>
                                <td style="padding:10px;"><?= date('d/m/Y', strtotime($m['data'])) ?> às <?= date('H:i', strtotime($m['hora'])) ?></td>
                                <td style="padding:10px;"><?= number_format($m['preco'], 2, ',', '.') ?>€</td>
                                <td style="padding:10px;"><strong><?= htmlspecialchars($m['estado']) ?></strong></td>
                                <td>
                                    <a href="editar-marcacao.php?id=<?= $m['id_marcacao'] ?>"
                                     style="background:#007bff; color:white; padding:6px 12px; border-radius:4px; 
                                     text-decoration:none; font-size:14px; margin-right: 5px;">
                                     Editar
                                </a>

                                <a href="minhas-marcacoes.php?cancelar=<?= $m['id_marcacao'] ?>"
                                    style="background:#dc3545; color:white; padding:6px 12px; border-radius:4px; text-decoration:none; font-size:14px;"
                                    onclick="return confirm('Tem a certeza que deseja cancelar esta marcação?');">
                                    Cancelar
                                </a>
                                </td>
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