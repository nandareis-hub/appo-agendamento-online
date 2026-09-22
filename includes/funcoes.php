<?php

/**
 * Verifica se existe um utilizador autenticado na sessão.
 *
 * @return bool
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Procura um utilizador pelo e-mail.
 *
 * @param PDO $pdo
 * @param string $email
 * @return array|null
 */
function obterUtilizadorPorEmail(PDO $pdo, string $email): ?array
{
    $sql = "
        SELECT
            id_utilizador,
            nome,
            email,
            password,
            telefone,
            tipo,
            data_registo
        FROM utilizadores
        WHERE email = :email
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);

    $utilizador = $stmt->fetch();

    return $utilizador ?: null;
}

/**
 * Regista um novo utilizador na base de dados.
 *
 * @param PDO $pdo
 * @param string $nome
 * @param string $email
 * @param string $password (Hash gerado por password_hash)
 * @param string|null $telefone
 * @param string $tipo ('cliente' por padrão)
 * @return bool
 */
function registarUtilizador(
    PDO $pdo,
    string $nome,
    string $email,
    string $password,
    ?string $telefone = null,
    string $tipo = 'cliente'
): bool {
    $sql = "
        INSERT INTO utilizadores
            (nome, email, password, telefone, tipo)
        VALUES
            (:nome, :email, :password, :telefone, :tipo)
    ";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':nome'     => $nome,
        ':email'    => $email,
        ':password' => $password,
        ':telefone' => $telefone,
        ':tipo'     => $tipo
    ]);
}