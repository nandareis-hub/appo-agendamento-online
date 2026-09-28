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

    $duracaoServico = 0;

    $stmtDuracao = $pdo->prepare("SELECT duracao FROM servicos WHERE id_servico = :servico");
    $stmtDuracao->execute([':servico' => $idServico]);
    $servico = $stmtDuracao->fetch();

    if ($servico) {
        $duracaoServico = (int) $servico['duracao'];
    }

    if (empty($idServico) || empty($idProfissional) || empty($dataInput) || empty($horaInput)) {
        $mensagemErro = "Por favor, preencha todos os campos do formulário.";
    } else {

        // Validar data e horário da marcação
        $dataSelecionada = new DateTime($dataInput);
        $hoje = new DateTime();

        // Não permitir datas anteriores a hoje
        if ($dataSelecionada < new DateTime($hoje->format('Y-m-d'))) {
            $mensagemErro = "Não é possível fazer uma marcação para uma data que já passou.";
        }

        // Não permitir marcações aos domingos
        elseif ($dataSelecionada->format('N') == 7) {
            $mensagemErro = "Não é possível fazer marcações aos domingos.";
        }

        // Horário de funcionamento: 09:00 às 18:00
        elseif ($horaInput < '09:00' || $horaInput > '18:00') {
            $mensagemErro = "O horário de funcionamento é das 09:00 às 18:00.";
        }
        // Não permitir horários que já passaram hoje
        elseif ($dataInput == date('Y-m-d') && $horaInput <= date('H:i')) {
            $mensagemErro = "O horário selecionado já passou.";
       }
       // Não permitir que o serviço termine depois das 18:00
       elseif (
           (new DateTime($dataInput . ' ' . $horaInput))
           ->modify("+{$duracaoServico} minutes")
           > new DateTime($dataInput . ' 18:00') 
           ) {
          $mensagemErro = "O horário escolhido não permite terminar o serviço até às 18:00.";
       }

       else {

            // Verificar se o profissional pode realizar o serviço
            $stmtVerifica = $pdo->prepare("
                SELECT * FROM profissional_servico
                WHERE id_profissional = :profissional
                AND id_servico = :servico
            ");

            $stmtVerifica->execute([
                ':profissional' => $idProfissional,
                ':servico'      => $idServico
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
                <input type="date" name="data" id="data" class="form-control" style="width:100%; padding:10px;" min="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="hora" style="display:block; font-weight:bold; margin-bottom: 5px;">Hora:</label>
                <input type="time" name="hora" id="hora" class="form-control" style="width:100%; padding:10px;" min="09:00" max="18:00" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-destaque" style="background-color: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
                    Confirmar Agendamento
                </button>
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

    // Verificar a duração do serviço
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
</script>

</body>
</html>