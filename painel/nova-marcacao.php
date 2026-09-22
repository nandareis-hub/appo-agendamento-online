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

// 1. Buscar Serviços e Profissionais ativos
$servicos = [];
$profissionais = [];

try {
    $stmtS = $pdo->query("SELECT * FROM servicos WHERE ativo = 1");
    $servicos = $stmtS->fetchAll();

    $stmtP = $pdo->query("SELECT * FROM profissionais WHERE ativo = 1");
    $profissionais = $stmtP->fetchAll();
} catch (PDOException $e) {
    $mensagemErro = "Erro ao carregar dados: " . $e->getMessage();
}

// 2. Processar Agendamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idServico = $_POST['id_servico'] ?? null;
    $idProfissional = $_POST['id_profissional'] ?? null;
    $dataInput = $_POST['data'] ?? null;
    $horaInput = $_POST['hora'] ?? null;

    if (empty($idServico) || empty($idProfissional) || empty($dataInput) || empty($horaInput)) {
        $mensagemErro = "Por favor, preencha todos os campos do formulário.";
    } else {

        // Verificar se o profissional pode realizar o serviço
        $stmtVerifica = $pdo->prepare("
        SELECT * FROM profissional_servico
        WHERE id_profissional = :profissional
        AND id_servico = :servico
    ");

    $stmtVerifica->execute([
        ':profissional' => $idProfissional,
        ':servico' => $idServico
    ]);

    $permissao = $stmtVerifica->fetch();

    if (!$permissao) {
        $mensagemErro = "A profissional selecionada não realiza este serviço.";
    } else {
        try {
            $sql = "INSERT INTO marcacoes (id_utilizador, id_profissional, id_servico, data, hora, estado) 
                    VALUES (:user_id, :profissional_id, :servico_id, :data, :hora, 'Pendente')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':user_id'         => $userId,
                ':profissional_id' => $idProfissional,
                ':servico_id'      => $idServico,
                ':data'            => $dataInput,
                ':hora'            => $horaInput
            ]);

            $mensagemSucesso = "Marcação agendada com sucesso!";
        } catch (PDOException $e) {
            $mensagemErro = "Erro ao guardar a marcação: " . $e->getMessage();
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
            <div class="alert erro" style="background:#f8d7da; color:#721c24; padding:10px; border-radius:4px; margin-bottom:15px;"><?= htmlspecialchars($mensagemErro) ?></div>
        <?php endif; ?>

        <?php if (!empty($mensagemSucesso)): ?>
            <div class="alert sucesso" style="background:#d4edda; color:#155724; padding:10px; border-radius:4px; margin-bottom:15px;"><?= htmlspecialchars($mensagemSucesso) ?></div>
        <?php endif; ?>

        <form action="nova-marcacao.php" method="POST">
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="id_servico" style="display:block; font-weight:bold; margin-bottom: 5px;">Serviço:</label>
                <select name="id_servico" id="id_servico" class="form-control" style="width:100%; padding:10px;" required>
                    <option value="" disabled selected>-- Selecione um serviço --</option>
                    <?php foreach ($servicos as $s): ?>
                        <option value="<?= $s['id_servico'] ?>">
                            <?= htmlspecialchars($s['nome']) ?> (<?= $s['duracao'] ?> min) - <?= number_format($s['preco'], 2, ',', '.') ?>€
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="id_profissional" style="display:block; font-weight:bold; margin-bottom: 5px;">Profissional:</label>
                <select name="id_profissional" id="id_profissional" class="form-control" style="width:100%; padding:10px;" required>
                    <option value="" disabled selected>-- Selecione um profissional --</option>
                    <?php foreach ($profissionais as $p): ?>
                        <option value="<?= $p['id_profissional'] ?>">
                            <?= htmlspecialchars($p['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="data" style="display:block; font-weight:bold; margin-bottom: 5px;">Data:</label>
                <input type="date" name="data" id="data" class="form-control" style="width:100%; padding:10px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="hora" style="display:block; font-weight:bold; margin-bottom: 5px;">Hora:</label>
                <input type="time" name="hora" id="hora" class="form-control" style="width:100%; padding:10px;" required>
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