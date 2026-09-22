document.addEventListener('DOMContentLoaded', function () {

    // Helper para ocultar todas as mensagens de erro
    function limparErros() {
        const msgs = document.querySelectorAll('.mensagem-erro');
        msgs.forEach(function (msg) {
            msg.style.display = 'none';
        });
    }

    // Helper para exibir mensagem de erro específica
    function exibirErro(idElemento, visivel = true) {
        const el = document.getElementById(idElemento);
        if (el) {
            el.style.display = visivel ? 'block' : 'none';
        }
    }

    // Validador de formato de e-mail básico
    function emailValido(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // ==========================================
    // 1. VALIDAÇÃO DO FORMULÁRIO DE LOGIN
    // ==========================================
    const formLogin = document.getElementById('form-login');

    if (formLogin) {
        formLogin.addEventListener('submit', function (e) {
            limparErros();
            let valido = true;

            const email = document.getElementById('email');
            const palavraPasse = document.getElementById('palavra_passe');

            if (!email || !emailValido(email.value.trim())) {
                exibirErro('erro-email');
                valido = false;
            }

            if (!palavraPasse || palavraPasse.value.trim() === '') {
                exibirErro('erro-senha');
                valido = false;
            }

            if (!valido) {
                e.preventDefault();
            }
        });
    }

    // ==========================================
    // 2. VALIDAÇÃO DO FORMULÁRIO DE REGISTO
    // ==========================================
    const formRegisto = document.getElementById('form-registo');

    if (formRegisto) {
        formRegisto.addEventListener('submit', function (e) {
            limparErros();
            let valido = true;

            const nome = document.getElementById('nome');
            const email = document.getElementById('email');
            const senha = document.getElementById('senha');
            const confirmar = document.getElementById('confirmar');

            if (!nome || nome.value.trim() === '') {
                exibirErro('erro-nome');
                valido = false;
            }

            if (!email || !emailValido(email.value.trim())) {
                exibirErro('erro-email');
                valido = false;
            }

            if (!senha || senha.value.length < 6) {
                exibirErro('erro-senha');
                valido = false;
            }

            if (!confirmar || senha.value !== confirmar.value) {
                exibirErro('erro-confirmar');
                valido = false;
            }

            if (!valido) {
                e.preventDefault();
            }
        });
    }
});