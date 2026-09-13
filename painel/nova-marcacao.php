<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Appo - Nova marcação</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/agenda.js" defer></script>
</head>
<body>
<div class="container">
    <header class="topo">
        <h1>Appo — Agendamentos Online</h1>

        <nav class="menu">
            <a href="home.html">Início</a>
            <a href="minhas-marcacoes.html">Minhas marcações</a>
            <a href="nova-marcacao.html">Nova marcação</a>
        </nav>
    </header>

    <main>
        <h2>Nova marcação</h2>

        <!-- Mensagem de sucesso -->
        <div id="mensagem-sucesso" class="alert sucesso" style="display:none;">
            <!-- Conteúdo preenchido via JS -->
        </div>

        <!-- Mensagem de erro -->
        <div id="mensagem-erro" class="alert erro" style="display:none;">
        </div>

        <!-- Mensagem quando não houver horários -->
        <p id="sem-horarios" style="display:none;">Não existem horários disponíveis neste momento.</p>

        <!-- Formulário de marcação -->
        <form id="form-marcacao" style="display:none;">
            <div class="form-group">
                <label for="id_horario">Selecione um horário:</label>

                <select name="id_horario" id="id_horario" required>
                    <option value="">-- Escolha um horário --</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit">Confirmar marcação</button>
            </div>
        </form>

    </main>
</div>

<!-- JS do frontend para preencher horários e enviar marcação -->
<script src="../js/nova-marcacao.js"></script>

</body>
</html>
