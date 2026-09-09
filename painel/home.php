<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appo - Painel Principal</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="navbar">
        <h1>Appo Agendamentos</h1>
        <nav>
            <a href="home.php" class="active">Início</a>
            <a href="minhas-marcacoes.php">Minhas Marcações</a>
            <a href="nova-marcacao.php">Nova Marcação</a>
            <a href="../auth/login.php" class="btn-sair">Sair</a>
        </nav>
    </header>

    <main class="main-content">
        <div class="welcome-box">
            <h2>Bem-vindo ao Salão de Beleza</h2>
            <p>Escolha uma das opções abaixo para gerir os seus agendamentos:</p>
        </div>

        <div class="cards-grid">
            <div class="card-option">
                <h3>Nova Marcação</h3>
                <p>Agende um novo serviço com as nossas profissionais.</p>
                <a href="nova-marcacao.php" class="btn">Agendar Agora</a>
            </div>

            <div class="card-option">
                <h3>Minhas Marcações</h3>
                <p>Consulte ou cancele as suas marcações efetuadas.</p>
                <a href="minhas-marcacoes.php" class="btn btn-secundario">Ver Agenda</a>
            </div>
        </div>
    </main>
</body>
</html>