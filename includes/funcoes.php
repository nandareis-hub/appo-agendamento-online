<?php

/**
 * Verifica se existe um utilizador autenticado.
 *
 * @return bool
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}


/**
 * Procura um utilizador pelo email.
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
            data_registo
        FROM utilizadores
        WHERE email = :email
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':email' => $email
    ]);

    $utilizador = $stmt->fetch();

    if ($utilizador === false) {
        return null;
    }

    return $utilizador;
}


/**
 * Regista um novo utilizador na base de dados.
 *
 * @param PDO $pdo
 * @param string $nome
 * @param string $email
 * @param string $password
 * @return bool
 */
function registarUtilizador(
    PDO $pdo,
    string $nome,
    string $email,
    string $password
): bool {
    $sql = "
        INSERT INTO utilizadores
            (nome, email, password)
        VALUES
            (:nome, :email, :password)
    ";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':password' => $password
    ]);
}