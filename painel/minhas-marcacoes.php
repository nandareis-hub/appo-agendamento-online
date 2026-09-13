<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Appo - Minhas marcações</title>
    <link rel="stylesheet" href="../css/style.css">
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

        <!-- Título dinâmico: cliente ou admin -->
        <h2 id="titulo-marcacoes">Minhas marcações</h2>

        <!-- Mensagem de sucesso (ex: cancelamento) -->
        <div id="mensagem-sucesso" class="alert sucesso" style="display:none;">
        </div>

        <!-- Mensagem quando não houver marcações -->
        <p id="sem-marcacoes" style="display:none;">Não existem marcações.</p>

        <!-- Tabela de marcações -->
        <table id="tabela-marcacoes" style="display:none;">
            <thead>
                <tr>
                    <!-- Cabeçalhos visíveis apenas para admin -->
                    <th class="col-admin" style="display:none;">Cliente</th>
                    <th class="col-admin" style="display:none;">Email</th>

                    <th>Data</th>
                    <th>Hora</th>
                    <th>Serviço</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody id="lista-marcacoes">
            </tbody>
        </table>

    </main>
</div>

<!-- JS do frontend para preencher dados -->
<script src="../js/minhas-marcacoes.js"></script>

</body>
</html>
