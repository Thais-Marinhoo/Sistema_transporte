window.onload = function(){

    const linhaModelo = document.querySelector(".linha-modelo");
    const corpoTabela = document.getElementById("corpoTabela");
    const btnAdd = document.getElementById("btnAdd");

    function criarLinha(){
        let novaLinha = linhaModelo.cloneNode(true);

        novaLinha.classList.remove("linha-modelo");
        novaLinha.style.display = "table-row";

        novaLinha.querySelectorAll("input, select").forEach(campo => {
            campo.removeAttribute("disabled");

            if(campo.tagName === 'INPUT') {
                campo.value = "";

                campo.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.style.border = '';
                        this.style.backgroundColor = '';
                    }
                });
            }
        });

        corpoTabela.appendChild(novaLinha);
    }

    btnAdd.addEventListener("click", function(){
        let quantidade = parseInt(document.getElementById("quantidadeLinhas").value) || 1;

        for(let i = 0; i < quantidade; i++){
            criarLinha();
        }
    });

    document.addEventListener("click", function(e){
        let botao = e.target.closest(".btn-remover");
        if(botao){
            botao.closest("tr").remove();
        }
    });

    const form = document.getElementById('formCA');

    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            // Remove mensagem de erro anterior
            const erroAntigo = document.getElementById('erro-dinamico-js');
            if (erroAntigo) erroAntigo.remove();

            let temErroCampo = false;

            let linhasAtivas = corpoTabela.querySelectorAll('tr:not(.linha-modelo)');

            for (let linha of linhasAtivas) {
                let inputNome     = linha.querySelector('input[name="nome[]"]');
                let inputEndereco = linha.querySelector('input[name="endereco[]"]');

                // Destaca campos vazios visualmente
                [inputNome, inputEndereco].forEach(input => {
                    if (input && input.value.trim() === '') {
                        temErroCampo = true;
                        input.style.border = "2px solid red";
                        input.style.backgroundColor = "#ffe6e6";
                    }
                });
            }

            if (temErroCampo) {
                const caixaErro = document.createElement('div');
                caixaErro.id = 'erro-dinamico-js';
                caixaErro.style.cssText =
                    "display:flex; align-items:center; gap:12px;" +
                    "background-color:#fff5f5; border-left:4px solid #e53e3e;" +
                    "border-radius:6px; padding:16px; margin:15px 0;" +
                    "box-shadow:0 2px 8px rgba(0,0,0,0.05);";

                caixaErro.innerHTML =
                    '<span class="material-icons" style="color:#e53e3e; font-size:24px;">error_outline</span>' +
                    '<p style="margin:0; color:#c53030; font-size:0.95rem; line-height:1.5; font-weight:500;">' +
                    'Por favor, preencha todos os campos. Não deixe linhas em branco.</p>';

                form.parentNode.insertBefore(caixaErro, form);
                window.scrollTo({ top: 0, behavior: 'smooth' });

            } else {
                // Tudo ok — envia direto para o PHP validar o endereço via Geoapify
                form.submit();
            }
        });
    }
};

// Limpar status da URL após exibição do alerta
if (window.location.search.includes('status=')) {
    window.history.replaceState({}, document.title, window.location.pathname);
}