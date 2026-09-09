document.addEventListener("DOMContentLoaded", function () {
    const formLogin = document.getElementById("form-login");
    const formRegisto = document.getElementById("form-registo");

    // Validação na página de Login
    if (formLogin) {
        formLogin.addEventListener("submit", function (e) {
            let valido = true;

            const email = document.getElementById("email");
            const senha = document.getElementById("palavra_passe");

            if (!email.value.includes("@") || email.value.trim() === "") {
                document.getElementById("erro-email").style.display = "block";
                valido = false;
            } else {
                document.getElementById("erro-email").style.display = "none";
            }

            if (senha.value.trim() === "") {
                document.getElementById("erro-senha").style.display = "block";
                valido = false;
            } else {
                document.getElementById("erro-senha").style.display = "none";
            }

            if (!valido) {
                e.preventDefault(); // Impede o envio do formulário se houver erro
            }
        });
    }

    // Validação na página de Registo
    if (formRegisto) {
        formRegisto.addEventListener("submit", function (e) {
            let valido = true;

            const nome = document.getElementById("nome");
            const email = document.getElementById("email");
            const telefone = document.getElementById("telefone");
            const senha = document.getElementById("palavra_passe");

            if (nome.value.trim() === "") {
                document.getElementById("erro-nome").style.display = "block";
                valido = false;
            } else {
                document.getElementById("erro-nome").style.display = "none";
            }

            if (!email.value.includes("@") || email.value.trim() === "") {
                document.getElementById("erro-email").style.display = "block";
                valido = false;
            } else {
                document.getElementById("erro-email").style.display = "none";
            }

            if (telefone.value.trim().length < 9) {
                document.getElementById("erro-telefone").style.display = "block";
                valido = false;
            } else {
                document.getElementById("erro-telefone").style.display = "none";
            }

            if (senha.value.length < 6) {
                document.getElementById("erro-senha").style.display = "block";
                valido = false;
            } else {
                document.getElementById("erro-senha").style.display = "none";
            }

            if (!valido) {
                e.preventDefault();
            }
        });
    }
});