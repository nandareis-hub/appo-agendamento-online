<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appo - Minhas Marcações</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="navbar">
        <h1>Appo Agendamentos</h1>
        <nav>
            <a href="home.php">Início</a>
            <a href="minhas-marcacoes.php" class="active">Minhas Marcações</a>
            <a href="nova-marcacao.php">Nova Marcação</a>
            <a href="../auth/login.php" class="btn-sair">Sair</a>
        </nav>
    </header>

    <main class="main-content">
        <div class="card-largo">
            <h2>As Minhas Marcações</h2>
            
            <table class="tabela-agendamentos">
                <thead>
                    <tr>
                        <th>Profissional</th>
                        <th>Serviço</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Exemplo estático de apresentação no frontend -->
                    <tr>
                        <td>Fernanda</td>
                        <td>Corte de Cabelo</td>
                        <td>15/09/2026</td>
                        <td>14:30</td>
                        <td><span class="badge pendente">Pendente</span></td>
                        <td>
                            <button class="btn-cancelar">Cancelar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>