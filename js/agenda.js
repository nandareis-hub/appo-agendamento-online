document.addEventListener('DOMContentLoaded', function () {
    
    // --- 1. PÁGINA: NOVA MARCAÇÃO ---
    const selectHorario = document.getElementById('id_horario');
    const formMarcacao = document.getElementById('form-marcacao');
    const msgErro = document.getElementById('mensagem-erro');
    const msgSucesso = document.getElementById('mensagem-sucesso');
    const semHorarios = document.getElementById('sem-horarios');

    if (formMarcacao && selectHorario) {
        
        // Revela o formulário se houver horários ou a mensagem caso contrário
        // (Ajustar com chamada Fetch caso os dados venham do PHP dinamicamente)
        if (selectHorario.options.length > 1) {
            formMarcacao.style.display = 'block';
            if (semHorarios) semHorarios.style.display = 'none';
        } else {
            if (semHorarios) semHorarios.style.display = 'block';
            formMarcacao.style.display = 'none';
        }

        // Validação ao submeter o formulário
        formMarcacao.addEventListener('submit', function (e) {
            if (!selectHorario.value) {
                e.preventDefault();
                
                if (msgErro) {
                    msgErro.textContent = 'Por favor, selecione um horário válido antes de confirmar.';
                    msgErro.style.display = 'block';
                }
                return false;
            }
            
            if (msgErro) msgErro.style.display = 'none';
        });

        // Oculta erro ao alterar a seleção
        selectHorario.addEventListener('change', function () {
            if (selectHorario.value && msgErro) {
                msgErro.style.display = 'none';
            }
        });
    }

    // --- 2. PÁGINA: MINHAS MARCAÇÕES / HOME ---
    const tabelaMarcacoes = document.getElementById('tabela-marcacoes');
    const listaMarcacoes = document.getElementById('lista-marcacoes');
    const semMarcacoes = document.getElementById('sem-marcacoes');

    // Se existirem linhas preenchidas pelo PHP/JS na tabela, exibe a tabela
    if (tabelaMarcacoes && listaMarcacoes) {
        const temLinhas = listaMarcacoes.querySelectorAll('tr').length > 0;
        
        if (temLinhas) {
            tabelaMarcacoes.style.display = 'table';
            if (semMarcacoes) semMarcacoes.style.display = 'none';
        } else {
            tabelaMarcacoes.style.display = 'none';
            if (semMarcacoes) semMarcacoes.style.display = 'block';
        }
    }
});