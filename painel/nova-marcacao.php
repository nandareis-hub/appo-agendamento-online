<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appo - Nova Marcação</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="navbar">
        <h1>Appo Agendamentos</h1>
        <nav>
            <a href="home.php">Início</a>
            <a href="minhas-marcacoes.php">Minhas Marcações</a>
            <a href="nova-marcacao.php" class="active">Nova Marcação</a>
            <a href="../auth/login.php" class="btn-sair">Sair</a>
        </nav>
    </header>

    <main class="main-content">
        <div class="card">
            <h2>Marcar Atendimento</h2>
            <form id="form-marcacao" action="nova-marcacao.php" method="POST">
                <div class="form-group">
                    <label for="id_profissional">Escolha o Profissional</label>
                    <select id="id_profissional" name="id_profissional" required>
                        <option value="">Selecione...</option>
                        <option value="1">Fernanda</option>
                        <option value="2">Thamires</option>
                        <option value="3">Mairane</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_servico">Escolha o Serviço</label>
                    <select id="id_servico" name="id_servico" required>
                        <option value="">Selecione primeiro o profissional...</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="data">Data</label>
                    <input type="date" id="data" name="data" required>
                </div>

                <div class="form-group">
                    <label for="hora">Hora</label>
                    <input type="time" id="hora" name="hora" required>
                </div>

                <button type="submit" class="btn">Confirmar Agendamento</button>
            </form>
        </div>
    </main>

    <script src="../js/agenda.js"></script>
</body>
</html>