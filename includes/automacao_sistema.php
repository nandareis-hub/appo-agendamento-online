<?php

// 1. Função para atualizar marcações passadas de 'Confirmada' para 'Concluída'
function atualizarEstadosExpirados($pdo) {
    $sql = "UPDATE marcacoes 
            SET estado = 'Concluída' 
            WHERE estado = 'Confirmada' 
              AND CONCAT(data_marcacao, ' ', hora_marcacao) < NOW()";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

// 2. Função para gerar notificações de lembrete para as marcações de amanhã
function processarLembretesAutomaticos($pdo) {
    // Procura marcações confirmadas agendadas para amanhã que ainda não receberam lembrete
    $sql = "SELECT id_marcacao, id_utilizador, data_marcacao, hora_marcacao 
            FROM marcacoes 
            WHERE estado = 'Confirmada' 
              AND lembrete_enviado = 0 
              AND data_marcacao = CURDATE() + INTERVAL 1 DAY";
              
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $marcacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($marcacoes as $m) {
        $mensagem = "Lembrete: Tem uma marcação agendada para amanhã às " . date('H:i', strtotime($m['hora_marcacao'])) . ".";

        // Inserir a notificação na tabela 'notificacoes'
        $stmtNotif = $pdo->prepare("INSERT INTO notificacoes (id_utilizador, mensagem) VALUES (?, ?)");
        $stmtNotif->execute([$m['id_utilizador'], $mensagem]);

        // Marcar a marcação como 'lembrete_enviado = 1' para não repetir a notificação
        $stmtUpdate = $pdo->prepare("UPDATE marcacoes SET lembrete_enviado = 1 WHERE id_marcacao = ?");
        $stmtUpdate->execute([$m['id_marcacao']]);
    }
}

// Executar automaticamente ambas as funções sempre que este ficheiro for incluído
atualizarEstadosExpirados($pdo);
processarLembretesAutomaticos($pdo);
?>