<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Resgatar os dados da sessão para o cabeçalho não quebrar
$nomeUtilizador = $_SESSION['user_nome'] ?? 'Utilizador';
$tipoUtilizador = $_SESSION['user_tipo'] ?? 'cliente';

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appo - Nova marcação</title>
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
        <a href="minhas-marcacoes.php">Minhas marcações</a>
        <a href="nova-marcacao.php" class="active btn-destaque">+ Nova marcação</a>
    </nav>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="card-conteudo">
        
        <h2>Nova marcação</h2>

        <!-- Mensagens Dinâmicas (Controladas via JS) -->
        <div id="mensagem-sucesso" class="alert sucesso" style="display:none;"></div>
        <div id="mensagem-erro" class="alert erro" style="display:none;"></div>

        <!-- Mensagem quando não houver horários -->
        <div id="sem-horarios" class="sem-dados" style="display:none;">
            <p>Não existem horários disponíveis neste momento.</p>
        </div>

        <!-- Formulário de marcação -->
        <form id="form-marcacao" style="display:none;">
            
            <div class="form-group">
                <label for="id_horario">Selecione um horário disponível:</label>
                <select name="id_horario" id="id_horario" class="form-control" required>
                    <option value="" disabled selected>-- Escolha um horário --</option>
                    <!-- Opções injetadas via JS -->
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Confirmar marcação</button>
            </div>
            
        </form>

    </main>
</div>

<!-- Único script necessário, carregado no final para garantir o DOM pronto -->
<script src="../js/agenda.js"></script>

</body>
</html>