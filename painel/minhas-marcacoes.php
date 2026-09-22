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

$nomeUtilizador = $_SESSION['user_nome'] ?? 'Utilizador';
$tipoUtilizador = $_SESSION['user_tipo'] ?? 'cliente';
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
        <a href="home.php">Início</a>
        <a href="minhas-marcacoes.php" class="active">Minhas marcações</a>
        <a href="nova-marcacao.php" class="btn-destaque">+ Nova marcação</a>
    </nav>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="card-conteudo">

        <h2 id="titulo-marcacoes">Minhas marcações</h2>

        <!-- Mensagens de Alerta Dinâmicas (JS) -->
        <div id="mensagem-sucesso" class="alert sucesso" style="display:none;"></div>
        <div id="mensagem-erro" class="alert erro" style="display:none;"></div>

        <!-- Mensagem quando não houver marcações -->
        <div id="sem-marcacoes" class="sem-dados" style="display:none;">
            <p>Não existem marcações registadas.</p>
            <a href="nova-marcacao.php" class="btn">Criar nova marcação</a>
        </div>

        <!-- Tabela de marcações envolvida em container responsivo -->
        <div class="table-responsive">
            <table id="tabela-marcacoes" class="tabela" style="display:none;">
                <thead>
                    <tr>
                        <!-- Cabeçalhos visíveis apenas para admin -->
                        <th class="col-admin" style="display:none;">Cliente</th>
                        <th class="col-admin" style="display:none;">E-mail</th>

                        <th>Data</th>
                        <th>Hora</th>
                        <th>Serviço</th>
                        <th>Estado</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody id="lista-marcacoes">
                    <!-- Preenchido via JavaScript -->
                </tbody>
            </table>
        </div>

    </main>

</div>

<!-- Inclusão do JavaScript do projeto (agenda.js) -->
<script src="../js/agenda.js" defer></script>

</body>
</html>