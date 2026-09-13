<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Appo - Painel</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <header class="topo">
        <h1>Appo — Agendamentos Online</h1>

        <div class="user-info">
            <span>Olá, <strong id="nome-utilizador">Utilizador</strong> (<span id="tipo-utilizador">cliente</span>)</span>
            <a class="btn-sair" href="../auth/logout.php">Sair</a>
        </div>
    </header>

    <!-- MENU -->
    <nav class="menu">
        <a href="home.html">Início</a>
        <a href="minhas-marcacoes.html">Minhas marcações</a>
        <a href="nova-marcacao.html">Nova marcação</a>
    </nav>

    <!-- CONTEÚDO -->
    <main>
        <h2>Resumo de marcações</h2>

        <p id="sem-marcacoes" style="display:none;">Não existem marcações registadas.</p>

        <!-- Tabela de marcações -->
        <table id="tabela-marcacoes" style="display:none;">
            <thead>
                <tr>
                    <!-- Estes cabeçalhos aparecem só se o utilizador for admin -->
                    <th class="col-admin" style="display:none;">Cliente</th>
                    <th class="col-admin" style="display:none;">Email</th>

                    <th>Data</th>
                    <th>Hora</th>
                    <th>Serviço</th>
                </tr>
            </thead>

            <tbody id="lista-marcacoes">
            </tbody>
        </table>

        <!-- Secção do administrador -->
        <section id="sec-admin" class="admin-info" style="display:none;">
            <h3>Área do administrador</h3>
            <p>Como administrador, pode gerir horários diretamente na base de dados ou criar páginas adicionais.</p>
        </section>

        <!-- Secção do cliente -->
        <section id="sec-cliente" class="cliente-info" style="display:none;">
            <h3>Área do cliente</h3>
            <p>Use o menu para criar novas marcações ou consultar as existentes.</p>
        </section>

    </main>
</div>

<script src="../js/home.js"></script>

</body>
</html>
