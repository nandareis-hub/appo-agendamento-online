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

        // Buscar duração do serviço
        $stmtDuracao = $pdo->prepare("
            SELECT duracao
            FROM servicos
            WHERE id_servico = :servico
        ");

        $stmtDuracao->execute([
            ':servico' => $idServico
        ]);

        $servico = $stmtDuracao->fetch();

        $duracaoServico = $servico ? (int) $servico['duracao'] : 0;

        // Validar data e horário
        $dataSelecionada = new DateTime($dataInput);
        $hoje = new DateTime();

        // Não permitir datas anteriores a hoje
        if ($dataSelecionada < new DateTime($hoje->format('Y-m-d'))) {

            $mensagemErro = "Não é possível fazer uma marcação para uma data que já passou.";

        // Não permitir domingos
        } elseif ($dataSelecionada->format('N') == 7) {

            $mensagemErro = "Não é possível fazer marcações aos domingos.";

        // Horário de funcionamento
        } elseif ($horaInput < '09:00' || $horaInput > '18:00') {

            $mensagemErro = "O horário de funcionamento é das 09:00 às 18:00.";

        // Não permitir horário que já passou hoje
        } elseif (
            $dataInput == date('Y-m-d') &&
            $horaInput <= date('H:i')
        ) {

            $mensagemErro = "O horário selecionado já passou.";

        // Verificar se o serviço termina depois das 18h
        } elseif (
            (new DateTime($dataInput . ' ' . $horaInput))
            ->modify("+{$duracaoServico} minutes")
            > new DateTime($dataInput . ' 18:00')
        ) {

            $mensagemErro = "O horário escolhido não permite terminar o serviço até às 18:00.";

        } else {

            // Verificar se a profissional realiza o serviço
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

                // Verificar se a profissional já possui outra marcação
                // na mesma data e horário
                $stmtConflito = $pdo->prepare("
                    SELECT id_marcacao
                    FROM marcacoes
                    WHERE id_profissional = :profissional
                    AND data = :data
                    AND hora = :hora
                    AND id_marcacao != :id
                    AND estado != 'Cancelada'
                    AND id_marcacao != :id
                    LIMIT 1
                ");

                $stmtConflito->execute([
                    ':profissional' => $idProfissional,
                    ':data' => $dataInput,
                    ':hora' => $horaInput,
                    ':id' => $idMarcacao
                ]);

                $conflito = $stmtConflito->fetch();

                if ($conflito) {

                    $mensagemErro = "A profissional selecionada já possui uma marcação para esta data e horário.";

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
        min="<?= date('Y-m-d') ?>"
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
        min="09:00"
        max="18:00"
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

<script>
document.getElementById('data').addEventListener('change', function() {

    const data = new Date(this.value + 'T00:00:00');

    if (data.getDay() === 0) {
        alert('Não é possível fazer marcações aos domingos.');
        this.value = '';
    }

});
</script>

<script>

const campoData = document.getElementById('data');
const campoHora = document.getElementById('hora');
const campoServico = document.getElementById('id_servico');

function atualizarHorario() {

    if (!campoData.value) {
        return;
    }

    const dataSelecionada = new Date(campoData.value + 'T00:00:00');
    const hoje = new Date();

    let horaMinima = '09:00';
    let horaMaxima = '18:00';

    // Se a data for hoje, não permitir horários que já passaram
    if (dataSelecionada.toDateString() === hoje.toDateString()) {

        const horas = String(hoje.getHours()).padStart(2, '0');
        const minutos = String(hoje.getMinutes()).padStart(2, '0');

        horaMinima = horas + ':' + minutos;
    }

    // Verificar duração do serviço
    const opcaoServico = campoServico.options[campoServico.selectedIndex];

    if (opcaoServico && opcaoServico.value) {

        const texto = opcaoServico.textContent;
        const resultado = texto.match(/\((\d+)\s*min\)/);

        if (resultado) {

            const duracao = parseInt(resultado[1]);

            const horaFim = new Date();
            horaFim.setHours(18, 0, 0, 0);
            horaFim.setMinutes(horaFim.getMinutes() - duracao);

            const horasFim = String(horaFim.getHours()).padStart(2, '0');
            const minutosFim = String(horaFim.getMinutes()).padStart(2, '0');

            horaMaxima = horasFim + ':' + minutosFim;
        }
    }

    campoHora.min = horaMinima;
    campoHora.max = horaMaxima;
}

campoData.addEventListener('change', atualizarHorario);
campoServico.addEventListener('change', atualizarHorario);

// Executar ao abrir a página
atualizarHorario();

</script>
</body>
</html>
            