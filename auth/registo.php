<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appo - Registo</titlPrazer em ve>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Criar Conta</h2>
            <form id="form-registo" action="registo.php" method="POST" novalidate>
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" placeholder="Seu nome completo">
                    <span class="mensagem-erro" id="erro-nome">O nome é obrigatório.</span>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="seu@email.com">
                    <span class="mensagem-erro" id="erro-email">Insira um e-mail válido.</span>
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="912345678">
                    <span class="mensagem-erro" id="erro-telefone">Insira um telefone válido (9 dígitos).</span>
                </div>

                <div class="form-group">
                    <label for="palavra_passe">Palavra-passe</label>
                    <input type="password" id="palavra_passe" name="palavra_passe" placeholder="Mínimo 6 caracteres">
                    <span class="mensagem-erro" id="erro-senha">A palavra-passe deve ter pelo menos 6 caracteres.</span>
                </div>

                <button type="submit" class="btn">Registar</button>
            </form>

            <div class="link-box">
                <p>Já tem conta? <a href="login.php">Faça Login</a></p>
            </div>
        </div>
    </div>

    <script src="../js/validacao.js"></script>
</body>
</html>