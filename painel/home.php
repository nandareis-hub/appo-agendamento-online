<?php
session_start();

require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

/*
 * Verificar se o utilizador está autenticado
 */
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

/*
 * Dados do utilizador que iniciou sessão
 */
$idUtilizador = $_SESSION['user_id'];
$nomeUtilizador = $_SESSION['user_nome'] ?? 'Utilizador';
$emailUtilizador = $_SESSION['user_email'] ?? '';
$tipoUtilizador = $_SESSION['user_tipo'] ?? 'cliente'; // ex: 'cliente' ou 'admin'

/*
 * Procurar as marcações do utilizador
 */
$sql = "
    SELECT
        m.id_marcacao,
        m.data,
        m.hora,
        m.estado,
        s.nome AS servico,
        p.nome AS profissional
    FROM marcacoes m
    INNER JOIN servicos s
        ON m.id_servico = s.id_servico
    INNER JOIN profissionais p
        ON m.id_profissional = p.id_profissional
    WHERE m.id_utilizador = :id_utilizador
    ORDER BY m.data ASC, m.hora ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id_utilizador' => $idUtilizador]);
$marcacoes = $stmt->fetchAll();
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

    <!-- CABEÇALHO DO PAINEL -->
    <header class="topo">
        <div class="brand">
            <h1>Appo — Agendamentos Online</h1>
        </div>

        <div class="user-info">
            <span>
                Olá, <strong id="nome-utilizador"><?= htmlspecialchars($nomeUtilizador) ?></strong>
                (<span id="tipo-utilizador"><?= htmlspecialchars($tipoUtilizador) ?></span>)
            </span>
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

        <h2>Resumo das minhas marcações</h2>

        <?php if (empty($marcacoes)): ?>

            <div class="sem-dados" id="sem-marcacoes">
                <p>Não existem marcações registadas de momento.</p>
                <a href="nova-marcacao.php" class="btn">Agendar novo serviço</a>
            </div>

        <?php else: ?>

            <!-- Wrapper responsivo para evitar quebra em telemóveis -->
            <div class="table-responsive">
                <table id="tabela-marcacoes" class="tabela">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Serviço</th>
                            <th>Profissional</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="lista-marcacoes">
                        <?php foreach ($marcacoes as $marcacao): ?>
                            <?php 
                                // Determinar a classe CSS para o badge de estado
                                $estadoClean = mb_strtolower(trim($marcacao['estado']));
                                $statusClass = match($estadoClean) {
                                    'confirmado', 'aprovado' => 'badge-sucesso',
                                    'pendente'              => 'badge-alerta',
                                    'cancelado'             => 'badge-erro',
                                    default                 => 'badge-padrao'
                                };
                            ?>
                            <tr>
                                <td><?= htmlspecialchars(date('d/m/Y', strtotime($marcacao['data']))) ?></td>
                                <td><?= htmlspecialchars(date('H:i', strtotime($marcacao['hora']))) ?></td>
                                <td><strong><?= htmlspecialchars($marcacao['servico']) ?></strong></td>
                                <td><?= htmlspecialchars($marcacao['profissional']) ?></td>
                                <td>
                                    <span class="badge <?= $statusClass ?>">
                                        <?= htmlspecialchars(ucfirst($marcacao['estado'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

        <!-- SEÇÕES INFORMATIVAS -->
        <?php if ($tipoUtilizador === 'admin'): ?>
            <section id="sec-admin" class="painel-info admin-info">
                <h3>Área do Administrador</h3>
                <p>Como administrador, pode gerir horários, serviços e utilizadores diretamente no sistema.</p>
            </section>
        <?php else: ?>
            <section id="sec-cliente" class="painel-info cliente-info">
                <h3>Ações Rápidas</h3>
                <p>Utilize o menu acima para agendar um novo serviço ou consultar o seu histórico de marcações.</p>
            </section>
        <?php endif; ?>


    </main>

</div>

<!-- Inclusão dos ficheiros JavaScript -->
<script src="../js/agenda.js" defer></script>

</body>

</html>