document.addEventListener("DOMContentLoaded", function () {
    const selectProfissional = document.getElementById("id_profissional");
    const selectServico = document.getElementById("id_servico");

    const servicosPorProfissional = {
        "1": [
            { id: 1, nome: "Corte" },
            { id: 2, nome: "Coloração" },
            { id: 3, nome: "Brushing" },
            { id: 4, nome: "Tratamento" }
        ],
        "2": [ 
            { id: 1, nome: "Corte" },
            { id: 2, nome: "Coloração" },
            { id: 3, nome: "Brushing" },
            { id: 4, nome: "Tratamento" }
        ],
        "3": [ 
            { id: 5, nome: "Penteado" },
            { id: 6, nome: "Maquilhagem" }
        ]
    };

    if (selectProfissional && selectServico) {
        selectProfissional.addEventListener("change", function () {
            const idProfissional = this.value;
            
            // Limpa o menu de serviços
            selectServico.innerHTML = '<option value="">Selecione um serviço...</option>';

            if (servicosPorProfissional[idProfissional]) {
                servicosPorProfissional[idProfissional].forEach(function (servico) {
                    const option = document.createElement("option");
                    option.value = servico.id;
                    option.textContent = servico.nome;
                    selectServico.appendChild(option);
                });
            }
        });
    }
});