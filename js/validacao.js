function validarLogin() {
    var email = document.getElementById('email');
    var senha = document.getElementById('senha');

    if (!email.value || !senha.value) {
        alert('Por favor, preencha email e senha.');
        return false;
    }

    return true;
}

function validarRegisto() {
    var nome = document.getElementById('nome');
    var email = document.getElementById('email');
    var senha = document.getElementById('senha');
    var confirmar = document.getElementById('confirmar');

    if (!nome.value || !email.value || !senha.value || !confirmar.value) {
        alert('Por favor, preencha todos os campos.');
        return false;
    }

    if (senha.value.length < 6) {
        alert('A senha deve ter pelo menos 6 caracteres.');
        return false;
    }

    if (senha.value !== confirmar.value) {
        alert('As senhas não coincidem.');
        return false;
    }

    return true;
}
