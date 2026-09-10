<?php

require_once __DIR__ . '/../includes/conexao.php';

/** @var PDO $pdo */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['palavra_passe'];

    // Query para verificar o utilizador
    $sql = "SELECT id_utilizador, nome, email, password
            FROM utilizadores
            WHERE email = :email
            AND password = :password";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':email' => $email,
        ':password' => $password
    ]);

    $utilizador = $stmt->fetch();

    if ($utilizador) {
        echo "Login realizado com sucesso!";
    } else {
        echo "E-mail ou palavra-passe incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appo - Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Appo Agendamentos</h2>
            <form id="form-login" action="login.php" method="POST" novalidate>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="seu@email.com">
                    <span class="mensagem-erro" id="erro-email">Insira um e-mail válido.</span>
                </div>

                <div class="form-group">
                    <label for="palavra_passe">Palavra-passe</label>
                    <input type="password" id="palavra_passe" name="palavra_passe" placeholder="******">
                    <span class="mensagem-erro" id="erro-senha">A palavra-passe é obrigatória.</span>
                </div>

                <button type="submit" class="btn">Entrar</button>
            </form>

            <div class="link-box">
                <p>Ainda não tem conta? <a href="registo.php">Registe-se aqui</a></p>
            </div>
        </div>
    </div>

    <script src="../js/validacao.js"></script>
</body>
</html>