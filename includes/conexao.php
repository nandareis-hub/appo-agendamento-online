<?php

// Configuração da ligação à base de dados

$host = "localhost";
$baseDados = "appo";
$utilizador = "root";
$password = "";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$baseDados;charset=utf8",
        $utilizador,
        $password
    );

    // Configuração para mostrar erros
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Configuração para devolver os resultados como array associativo
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $erro) {

    die("Erro na ligação à base de dados: " . $erro->getMessage());

}
?>