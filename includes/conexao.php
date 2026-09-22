<?php

// Configuração da ligação à base de dados
$host = "localhost";
$baseDados = "appo";
$utilizador = "root";
$password = "";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$baseDados;charset=utf8mb4",
        $utilizador,
        $password,
        [
            // Garante o tratamento correto de carateres e acentuação
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );

    // Configuração para lançar exceções em caso de erros SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Configuração para devolver os resultados como array associativo por padrão
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $erro) {

    // Mensagem amigável para o utilizador sem expor a estrutura interna (ideal para apresentação)
    die("Erro na ligação à base de dados. Verifique se o MySQL / XAMPP está ativo. Detalhe: " . $erro->getMessage());

}