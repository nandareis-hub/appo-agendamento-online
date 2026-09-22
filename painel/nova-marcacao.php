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

// Buscar Serviços e Profissionais da Base de Dados
$servicos = [];
$profissionais = [];

try {
    $stmtS = $pdo->query("SELECT * FROM servicos");
    $servicos = $stmtS->fetchAll();

    $stmtP = $pdo->query("SELECT * FROM profissionais");
    $profissionais = $stmtP->fetchAll();
} catch (PDOException $e) {
    $mensagemErro = "Erro ao carregar dados: " . $e->getMessage();
}

// Processar Agendamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idServico = $_POST['id_servico'] ?? null;
    $idProfissional = $_POST['id_profissional'] ?? null;
    $dataHora = $_POST['data_hora'] ?? null;

    if (empty($idServico) || empty($dataHora)) {
        $mensagemErro = "Por favor, preencha todos os campos obrigatórios.";
    } else {
        try {
            $sql = "INSERT INTO marcacoes (id_utilizador, id_servico, id_profissional, data_marcacao) 
                    VALUES (:user_id, :servico_id, :profissional_id, :data_hora)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':servico_id' => $idServico,
                ':profissional_id' => $idProfissional ?: null,
                ':data_hora' => $dataHora
            ]);

            $mensagemSucesso = "Marcação agendada com sucesso!";
        } catch (PDOException $e) {
            $mensagemErro = "Erro ao guardar a marcação: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appo - Nova Marcação</title>
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
        <a href="minhas-marcacoes.php">Minhas marcações</a>
        <a href="nova-marcacao.php" class="active btn-destaque">+ Nova marcação</a>
    </nav>

    <main class="card-conteudo">
        <h2>Nova marcação</h2>

        <?php if (!empty($mensagemErro)): ?>
            <div class="alert erro"><?= htmlspecialchars($mensagemErro) ?></div>
        <?php endif; ?>

        <?php if (!empty($mensagemSucesso)): ?>
            <div class="alert sucesso"><?= htmlspecialchars($mensagemSucesso) ?></div>
        <?php endif; ?>

        <form action="nova-marcacao.php" method="POST">
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="id_servico" style="display:block; font-weight:bold; margin-bottom: 5px;">Serviço:</label>
                <select name="id_servico" id="id_servico" class="form-control" style="width:100%; padding:10px;" required>
                    <option value="" disabled selected>-- Selecione um serviço --</option>
                    <?php foreach ($servicos as $s): ?>
                        <option value="<?= $s['id_servico'] ?? $s['id'] ?>">
                            <?= htmlspecialchars($s['nome'] ?? $s['nome_servico'] ?? 'Serviço') ?> 
                            <?= isset($s['preco']) ? '- ' . $s['preco'] . '€' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="id_profissional" style="display:block; font-weight:bold; margin-bottom: 5px;">Profissional (Opcional):</label>
                <select name="id_profissional" id="id_profissional" class="form-control" style="width:100%; padding:10px;">
                    <option value="">-- Qualquer profissional disponível --</option>
                    <?php foreach ($profissionais as $p): ?>
                        <option value="<?= $p['id_profissional'] ?? $p['id'] ?>">
                            <?= htmlspecialchars($p['nome'] ?? 'Profissional') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="data_hora" style="display:block; font-weight:bold; margin-bottom: 5px;">Data e Hora:</label>
                <input type="datetime-local" name="data_hora" id="data_hora" class="form-control" style="width:100%; padding:10px;" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-destaque" style="background-color: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
                    Confirmar Agendamento
                </button>
            </div>
        </form>
    </main>
</div>

</body>
</html>