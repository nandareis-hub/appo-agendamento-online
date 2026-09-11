document.addEventListener('DOMContentLoaded', function () {
    var selectHorario = document.getElementById('id_horario');
    var formMarcacao = document.getElementById('form-marcacao');

    if (selectHorario && formMarcacao) {
        formMarcacao.addEventListener('submit', function (e) {
            if (!selectHorario.value) {
                e.preventDefault();
                alert('Selecione um horário antes de confirmar a marcação.');
            }
        });

        selectHorario.addEventListener('change', function () {
            if (selectHorario.value) {
                console.log('Horário selecionado: ' + selectHorario.value);
            }
        });
    }
});
