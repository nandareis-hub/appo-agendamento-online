<?php
session_start();

require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

if (!isLoggedIn()) {
    header('Location: ../auth/login.php');
    exit;
}

$userId = $_SESSION['user_id'];

$mensagemErro = '';
$mensagemSucesso = '';

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

$idMarcacao = $_GET['id'] ?? null;

// Processar alteração da marcação
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
            SELECT *
            FROM profissional_servico
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

                $sql = "
                    UPDATE marcacoes
                    SET id_profissional = :profissional_id,
                        id_servico = :servico_id,
                        data = :data,
                        hora = :hora
                    WHERE id_marcacao = :id
                    AND id_utilizador = :user_id
                ";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    ':profissional_id' => $idProfissional,
                    ':servico_id' => $idServico,
                    ':data' => $dataInput,
                    ':hora' => $horaInput,
                    ':id' => $idMarcacao,
                    ':user_id' => $userId
                ]);

                $mensagemSucesso = "Marcação alterada com sucesso!";

            } catch (PDOException $e) {

                $mensagemErro = "Erro ao alterar a marcação: " . $e->getMessage();

            }
        }
    }
}

if (empty($idMarcacao)) {
    $mensagemErro = "Marcação não encontrada.";
} else {
    $stmt = $pdo->prepare("
        SELECT *
        FROM marcacoes
        WHERE id_marcacao = :id
        AND id_utilizador = :user_id
    ");

    $stmt->execute([
        ':id' => $idMarcacao,
        ':user_id' => $userId
    ]);

    $marcacao = $stmt->fetch();

    if (!$marcacao) {
        $mensagemErro = "Marcação não encontrada.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appo - Editar Marcação</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<div class="container layout-painel">
    <header class="topo">
        <div class="brand">
            <h1>Appo — Agendamentos Online</h1>
        </div>

        <div class="user-info">
            <span>
                Olá,
                <strong><?= htmlspecialchars($_SESSION['user_nome']) ?></strong>
            </span>
            <a class="btn-sair" href="../auth/logout.php">Sair</a>
        </div>

    </header>

    <nav class="menu">
        <a href="home.php">Início</a>
        <a href="minhas-marcacoes.php">Minhas marcações</a>
        <a href="nova-marcacao.php" class="btn-destaque">+ Nova marcação</a>
    </nav>

    <main class="card-conteudo">

        <h2>Editar marcação</h2>

        <?php if (!empty($mensagemErro)): ?>

            <div class="alert erro"
                style="background:#f8d7da; color:#721c24; padding:10px; border-radius:4px; margin-bottom:15px;">

                <?= htmlspecialchars($mensagemErro) ?>

            </div>
        <?php endif; ?>


        <?php if (!empty($mensagemSucesso)): ?>

            <div class="alert sucesso"
                style="background:#d4edda; color:#155724; padding:10px; border-radius:4px; margin-bottom:15px;">

                <?= htmlspecialchars($mensagemSucesso) ?>

            </div>

        <?php endif; ?>


<form action="editar-marcacao.php?id=<?= $idMarcacao ?>" method="POST">

<div class="form-group" style="margin-bottom: 15px;">
    <label for="id_servico"
         style="display:block; font-weight:bold; margin-bottom: 5px;">

        Serviço:
        </label>
                <select
                    name="id_servico"
                    id="id_servico"
                    class="form-control"
                    style="width:100%; padding:10px;"
                    required>

                    <?php foreach ($servicos as $s): ?>

                        <option
                            value="<?= $s['id_servico'] ?>"
                            <?= ($s['id_servico'] == $marcacao['id_servico']) ? 'selected' : '' ?>>

                            <?= htmlspecialchars($s['nome']) ?>
                            (<?= $s['duracao'] ?> min) -
                            <?= number_format($s['preco'], 2, ',', '.') ?>€

                        </option>

                    <?php endforeach; ?>

                </select>
</div>

<div class="form-group" style="margin-bottom: 15px;">
    <label for="id_profissional"
        style="display:block; font-weight:bold; margin-bottom: 5px;">
        Profissional:
    </label>

    <select
        name="id_profissional"
        id="id_profissional"
        class="form-control"
        style="width:100%; padding:10px;"
        required>

        <?php foreach ($profissionais as $p): ?>

            <option
                value="<?= $p['id_profissional'] ?>"
                <?= ($p['id_profissional'] == $marcacao['id_profissional']) ? 'selected' : '' ?>>

                <?= htmlspecialchars($p['nome']) ?>

            </option>

        <?php endforeach; ?>

    </select>
</div>
<div class="form-group" style="margin-bottom: 15px;">
    <label for="data"
        style="display:block; font-weight:bold; margin-bottom: 5px;">
        Data:
    </label>

    <input
        type="date"
        name="data"
        id="data"
        class="form-control"
        style="width:100%; padding:10px;"
        value="<?= htmlspecialchars($marcacao['data']) ?>"
        required>
</div>
<div class="form-group" style="margin-bottom: 20px;">
    <label for="hora"
        style="display:block; font-weight:bold; margin-bottom: 5px;">
        Hora:
    </label>

    <input
        type="time"
        name="hora"
        id="hora"
        class="form-control"
        style="width:100%; padding:10px;"
        value="<?= htmlspecialchars($marcacao['hora']) ?>"
        required>
</div>
<div class="form-actions" style="margin-top: 20px;">

    <button
        type="submit"
        class="btn btn-destaque"
        style="background-color: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
        Guardar alterações
    </button>

    <a
        href="minhas-marcacoes.php"
        style="background-color: #6c757d; color: white; padding: 12px 24px; border-radius: 4px; text-decoration: none; font-size: 16px;">
        Voltar
    </a>

</div>

</form>

    </main>

</div>

</body>
</html>
            