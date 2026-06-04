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
        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            // remove mensagem anterior
            const erroAntigo = document.getElementById('erro-dinamico-js');
            if (erroAntigo) erroAntigo.remove();

            let temErroCampo = false;
            let temErroEndereco = false;

            let linhasVisiveis = form.querySelectorAll('tr:not(.linha-modelo)');

            for (let linha of linhasVisiveis) {

                let inputNome = linha.querySelector('input[name="nome[]"]');
                let inputEndereco = linha.querySelector('input[name="endereco[]"]');

                [inputNome, inputEndereco].forEach(input => {
                    if (input && input.value.trim() === '') {
                        temErroCampo = true;
                    }
                });

                if (inputEndereco && inputEndereco.value.trim() !== '') {

                    let enderecoFiltrado = inputEndereco.value.trim() + ", Crateús, Ceará, Brasil";
                    let urlApi = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(enderecoFiltrado)}&format=json&limit=1`;

                    try {
                        let resposta = await fetch(urlApi);
                        let dados = await resposta.json();

                        if (!dados || dados.length === 0 || !dados[0].lat) {
                            temErroEndereco = true;

                            inputEndereco.style.border = "2px solid red";
                            inputEndereco.style.backgroundColor = "#ffe6e6";
                        }

                    } catch (error) {
                        console.error(error);
                        temErroEndereco = true;
                    }
                }
            }

            // 🔥 MENSAGEM ESTILO alertas.php
            if (temErroCampo || temErroEndereco) {

                let mensagem = 'Por favor, preencha todos os campos. Não deixe linhas em branco.';

                if (temErroEndereco && !temErroCampo) {
                    mensagem = 'Um ou mais endereços não foram localizados em Crateús. Verifique a ortografia.';
                } else if (temErroCampo && temErroEndereco) {
                    mensagem = 'Verifique os campos destacados: há informações em branco e endereços inválidos.';
                }

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
                    mensagem + '</p>';

                form.parentNode.insertBefore(caixaErro, form);
                window.scrollTo({ top: 0, behavior: 'smooth' });

            } else {
                form.submit();
            }
        });
    }
};

// limpar URL
if (window.location.search.includes('status=')) {
    window.history.replaceState({}, document.title, window.location.pathname);
}