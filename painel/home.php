<?php

session_start();

echo "<h1>Login realizado com sucesso!</h1>";

echo "<p>ID: " . ($_SESSION['user_id'] ?? 'não definido') . "</p>";

echo "<p>Nome: " . ($_SESSION['user_nome'] ?? 'não definido') . "</p>";

echo "<p>E-mail: " . ($_SESSION['user_email'] ?? 'não definido') . "</p>";