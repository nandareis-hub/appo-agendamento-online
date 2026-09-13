<?php

session_start();

require_once __DIR__ . '/../includes/conexao.php';

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

$stmt->execute([
    ':id_utilizador' => $idUtilizador
]);

$marcacoes = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Appo - Painel</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<div class="container">

    <header class="topo">

        <h1>Appo — Agendamentos Online</h1>

        <div class="user-info">

            <span>
                Olá,
                <strong id="nome-utilizador">
                    <?= htmlspecialchars($nomeUtilizador) ?>
                </strong>

                (<span id="tipo-utilizador">cliente</span>)
            </span>

            <a class="btn-sair" href="../auth/logout.php">
                Sair
            </a>

        </div>

    </header>


    <!-- MENU -->

    <nav class="menu">

        <a href="home.php">
            Início
        </a>

        <a href="minhas-marcacoes.php">
            Minhas marcações
        </a>

        <a href="nova-marcacao.php">
            Nova marcação
        </a>

    </nav>


    <!-- CONTEÚDO -->

    <main>

        <h2>Resumo de marcações</h2>


        <?php if (empty($marcacoes)): ?>

            <!-- Não existem marcações -->

            <p id="sem-marcacoes">
                Não existem marcações registadas.
            </p>


        <?php else: ?>

            <!-- Existem marcações -->

            <table id="tabela-marcacoes">

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

                        <tr>

                            <td>
                                <?= htmlspecialchars(
                                    date('d/m/Y', strtotime($marcacao['data']))
                                ) ?>
                            </td>


                            <td>
                                <?= htmlspecialchars(
                                    date('H:i', strtotime($marcacao['hora']))
                                ) ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($marcacao['servico']) ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($marcacao['profissional']) ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($marcacao['estado']) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        <?php endif; ?>


        <!-- Secção do administrador -->

        <section id="sec-admin" class="admin-info" style="display:none;">

            <h3>Área do administrador</h3>

            <p>
                Como administrador, pode gerir horários diretamente
                na base de dados ou criar páginas adicionais.
            </p>

        </section>


        <!-- Secção do cliente -->

        <section id="sec-cliente" class="cliente-info">

            <h3>Área do cliente</h3>

            <p>
                Use o menu para criar novas marcações
                ou consultar as existentes.
            </p>

        </section>


    </main>

</div>


</body>

</html>